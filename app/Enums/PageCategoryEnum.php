<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PageCategoryEnum: string implements HasLabel
{
    case sss = 'Staff Self Service';
    case pc = 'Payroll and Compensation';
    case la = 'Leave and Attendance';
    case be = 'Benefits and Entitlements';
    case pm = 'Performance Management';
    case cc = 'Communication and Collaboration';
    case em = 'Expense Management';
    case oo = 'Onboarding and Offboarding';
    case sa = 'Self-Service Analytics';
    case sh = 'Support and Helpdesk';
    case op = 'Operations';
    case sp = 'Support';
    case lm = 'Learning';
    case kb = 'Knowledge Base';
    case ip = 'Ideas';

    public function getLabel(): string
    {
        return match ($this) {
            self::sss => 'Staff Self Service',
            self::pc => 'Payroll and Compensation',
            self::la => 'Leave and Attendance',
            self::be => 'Benefits and Entitlements',
            self::pm => 'Performance Management',
            self::cc => 'Communication and Collaboration',
            self::em => 'Expense Management',
            self::oo => 'Onboarding and Offboarding',
            self::sa => 'Self-Service Analytics',
            self::sh => 'Support and Helpdesk',
            self::op => 'Operations',
            self::sp => 'Support',
            self::lm => 'Learning',
            self::kb => 'Knowledge Base',
            self::ip => 'Ideas',
        };
    }
}
