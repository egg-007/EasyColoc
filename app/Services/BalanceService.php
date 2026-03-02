<?php

namespace App\Services;

use App\Models\Colocation;

class BalanceService
{
    public function calculate(Colocation $colocation): array
    {
        $members = $colocation->users()
            ->wherePivotNull('left_at')
            ->get();

        $expenses = $colocation->expenses;

        $totalExpenses = $expenses->sum('amount');

        $memberCount = $members->count();

        if ($memberCount === 0) {
            return [];
        }

        $sharePerMember = $totalExpenses / $memberCount;

        $balances = [];

        foreach ($members as $member) {

            $paid = $expenses
                ->where('payer_id', $member->id)
                ->sum('amount');

            $balances[$member->id] = [
                'user'    => $member,
                'paid'    => $paid,
                'share'   => $sharePerMember,
                'balance' => $paid - $sharePerMember,
            ];
        }

        return $balances;
    }

    public function settlements(Colocation $colocation): array
    {
        $balances = $this->calculate($colocation);

        $creditors = [];
        $debtors   = [];

        foreach ($balances as $data) {
            if ($data['balance'] > 0) {
                $creditors[] = $data;
            } elseif ($data['balance'] < 0) {
                $debtors[] = $data;
            }
        }

        $settlements = [];

        foreach ($debtors as &$debtor) {

            $debt = abs($debtor['balance']);

            foreach ($creditors as &$creditor) {

                if ($debt <= 0) break;

                if ($creditor['balance'] <= 0) continue;

                $amount = min($debt, $creditor['balance']);

                $settlements[] = [
                    'from'   => $debtor['user'],
                    'to'     => $creditor['user'],
                    'amount' => round($amount, 2),
                ];

                $debt -= $amount;
                $creditor['balance'] -= $amount;
            }
        }

        return $settlements;
    }
}