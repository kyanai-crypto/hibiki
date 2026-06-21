<?php

namespace App\Services;

use App\Models\BusinessDay;
use App\Models\ClosedDay;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    /**
     * 指定月のカレンダーデータを返す
     *
     * @return Collection<string, array> キー=日付文字列, 値=便ごとの空き状況
     */
    public function getMonthData(int $year, int $month): Collection
    {
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end   = $start->copy()->endOfMonth();

        $defaultCapacity  = (int) Setting::get('default_capacity', 10);
        $thresholdFew     = (int) Setting::get('capacity_threshold_few', 80);
        $defaultMorning   = (bool) Setting::get('morning_open', true);
        $defaultAfternoon = (bool) Setting::get('afternoon_open', false);
        $defaultNight     = (bool) Setting::get('night_open', false);

        // 期間内のDBデータを一括取得
        $businessDays = BusinessDay::whereBetween('date', [$start, $end])
            ->get()->keyBy(fn($d) => $d->date->toDateString());

        $closedDays = ClosedDay::active()->get();

        // 承認済み予約の人数を日付・便でグループ集計
        $reservedCounts = Reservation::approved()
            ->whereBetween('reserved_date', [$start, $end])
            ->selectRaw('reserved_date, trip_type, SUM(num_people) as total')
            ->groupBy('reserved_date', 'trip_type')
            ->get()
            ->groupBy(fn($r) => $r->reserved_date)
            ->map(fn($group) => $group->keyBy('trip_type'));

        $result = collect();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateStr     = $date->toDateString();
            $businessDay = $businessDays->get($dateStr);

            // 定休日・休船判定
            $isHoliday = $businessDay?->is_holiday
                ?? ClosedDay::isClosedOn($date);

            if ($isHoliday) {
                $result->put($dateStr, ['status' => 'holiday', 'trips' => []]);
                continue;
            }

            $trips = [];
            foreach (['morning', 'afternoon', 'night'] as $trip) {
                // 営業有無判定
                $isOpen = $businessDay
                    ? $businessDay->isOpenFor($trip)
                    : match ($trip) {
                        'morning'   => $defaultMorning,
                        'afternoon' => $defaultAfternoon,
                        'night'     => $defaultNight,
                    };

                if (! $isOpen) {
                    continue;
                }

                // 定員
                $capacity = $businessDay?->getCapacityFor($trip) ?? $defaultCapacity;

                // 予約済み人数
                $reserved = (int) ($reservedCounts->get($dateStr)?->get($trip)?->total ?? 0);

                $trips[$trip] = $this->calcAvailability($reserved, $capacity, $thresholdFew);
            }

            $result->put($dateStr, [
                'status' => empty($trips) ? 'closed' : 'open',
                'trips'  => $trips,
            ]);
        }

        return $result;
    }

    /**
     * 空き状況を判定
     * @return array{label: string, icon: string, reserved: int, capacity: int}
     */
    private function calcAvailability(int $reserved, int $capacity, int $threshold): array
    {
        $rate = $capacity > 0 ? ($reserved / $capacity) * 100 : 100;

        if ($rate >= 100) {
            $icon  = '×';
            $label = '満席';
        } elseif ($rate >= $threshold) {
            $icon  = '△';
            $label = '残りわずか';
        } else {
            $icon  = '○';
            $label = '予約可';
        }

        return [
            'icon'      => $icon,
            'label'     => $label,
            'reserved'  => $reserved,
            'capacity'  => $capacity,
            'remaining' => $capacity - $reserved,
        ];
    }
}
