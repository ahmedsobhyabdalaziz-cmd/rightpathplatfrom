<?php

namespace App\Services;

use App\Models\Module;
use App\Models\User;
use App\Models\Enrollment;
use Carbon\Carbon;

class DripContentService
{
    /**
     * Check if a module is available for a user based on drip settings.
     */
    public function isModuleAvailable(Module $module, User $user): bool
    {
        // Admins always have access
        if ($user->isAdmin()) {
            return true;
        }

        // If no drip days, always available
        if ($module->drip_days === 0) {
            return true;
        }

        $enrollment = $user->getEnrollment($module->course);

        if (!$enrollment) {
            return false;
        }

        return $this->hasModuleUnlocked($module, $enrollment);
    }

    /**
     * Check if module has unlocked based on enrollment date.
     */
    public function hasModuleUnlocked(Module $module, Enrollment $enrollment): bool
    {
        $unlockDate = $this->getUnlockDate($module, $enrollment);

        return now()->gte($unlockDate);
    }

    /**
     * Get the unlock date for a module.
     */
    public function getUnlockDate(Module $module, Enrollment $enrollment): Carbon
    {
        return $enrollment->enrolled_at->addDays($module->drip_days);
    }

    /**
     * Get days until module unlocks.
     */
    public function getDaysUntilUnlock(Module $module, Enrollment $enrollment): int
    {
        $unlockDate = $this->getUnlockDate($module, $enrollment);

        if (now()->gte($unlockDate)) {
            return 0;
        }

        return now()->diffInDays($unlockDate);
    }

    /**
     * Get all available modules for a user in a course.
     */
    public function getAvailableModules(User $user, $course): array
    {
        $enrollment = $user->getEnrollment($course);

        if (!$enrollment) {
            return [];
        }

        return $course->modules
            ->filter(fn ($module) => $this->isModuleAvailable($module, $user))
            ->values()
            ->all();
    }

    /**
     * Get all locked modules for a user in a course.
     */
    public function getLockedModules(User $user, $course): array
    {
        $enrollment = $user->getEnrollment($course);

        if (!$enrollment) {
            return $course->modules->all();
        }

        return $course->modules
            ->filter(fn ($module) => !$this->isModuleAvailable($module, $user))
            ->map(function ($module) use ($enrollment) {
                $module->unlock_date = $this->getUnlockDate($module, $enrollment);
                $module->days_until_unlock = $this->getDaysUntilUnlock($module, $enrollment);
                return $module;
            })
            ->values()
            ->all();
    }

    /**
     * Get the next module to unlock for a user.
     */
    public function getNextUnlockModule(User $user, $course): ?Module
    {
        $lockedModules = $this->getLockedModules($user, $course);

        if (empty($lockedModules)) {
            return null;
        }

        return collect($lockedModules)
            ->sortBy('unlock_date')
            ->first();
    }
}










