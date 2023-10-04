<div>
    <div style="max-width: 200px;">
        @if($passwordChecker->passwordStrength < config('square-ui.password_strength_checker.good'))
            <div class="w-full grid grid-cols-12 gap-2 h-1 mt-3">
                <div class="col-span-3 h-full rounded bg-danger"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
            </div>
            <div class="text-danger mt-2">{{__('Very weak password')}}</div>
        @elseif($passwordChecker->passwordStrength < config('square-ui.password_strength_checker.strong'))
            <div class="w-full grid grid-cols-12 gap-2 h-1 mt-3">
                <div class="col-span-3 h-full rounded bg-orange-400"></div>
                <div class="col-span-3 h-full rounded bg-orange-400"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
            </div>
            <div class="text-orange-400 mt-2">{{__('weak password')}}</div>
        @elseif($passwordChecker->passwordStrength < config('square-ui.password_strength_checker.very_strong'))
            <div class="w-full grid grid-cols-12 gap-2 h-1 mt-3">
                <div class="col-span-3 h-full rounded bg-warning"></div>
                <div class="col-span-3 h-full rounded bg-warning"></div>
                <div class="col-span-3 h-full rounded bg-warning"></div>
                <div class="col-span-3 h-full rounded bg-slate-300"></div>
            </div>
            <div class="text-warning mt-2">{{__('Good password')}}</div>
        @else
            <div class="w-full grid grid-cols-12 gap-2 h-1 mt-3">
                <div class="col-span-3 h-full rounded bg-positive-500"></div>
                <div class="col-span-3 h-full rounded bg-positive-500"></div>
                <div class="col-span-3 h-full rounded bg-positive-500"></div>
                <div class="col-span-3 h-full rounded bg-positive-500"></div>
            </div>
            <div class="text-positive-500 mt-2">{{__('Strong password')}}</div>
        @endif
    </div>
    <ul class="mt-2 ml-1 text-xs">
        <li @class(['text-positive-500' => $passwordChecker->length, 'text-slate-400 opacity-70' => !$passwordChecker->length])> <i class="fa-solid fa-check mr-1"></i> @lang('Password is 8 characters long.')</li>
        <li @class(['text-positive-500' => $passwordChecker->hasCapitalLetter, 'text-slate-400 opacity-70' => !$passwordChecker->hasCapitalLetter])> <i class="fa-solid fa-check mr-1"></i> @lang('Password has uppercase letters.')</li>
        <li @class(['text-positive-500' => $passwordChecker->hasSmallLetter, 'text-slate-400 opacity-70' => !$passwordChecker->hasSmallLetter])> <i class="fa-solid fa-check mr-1"></i> @lang('Password has lowercase letters.')</li>
        <li @class(['text-positive-500' => $passwordChecker->hasNumbers, 'text-slate-400 opacity-70' => !$passwordChecker->hasNumbers])> <i class="fa-solid fa-check mr-1"></i> @lang('Password has a number.')</li>
        <li @class(['text-positive-500' => $passwordChecker->hasSpecial, 'text-slate-400 opacity-70' => !$passwordChecker->hasSpecial])> <i class="fa-solid fa-check mr-1"></i> @lang('Password has special characters'): @$!%*#?&</li>
        <li @class(['text-positive-500' => $passwordChecker->isUncompromised, 'text-slate-400 opacity-70' => !$passwordChecker->isUncompromised])> <i class="fa-solid fa-check mr-1"></i>@lang('Password is not compromised')</li>
    </ul>
</div>