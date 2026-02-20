<?php
declare(strict_types=1);

/**
 * Расчёт BMR (Mifflin-St Jeor) и суточной калорийности.
 * Также расчёт БЖУ в ккал и граммах.
 */
function kbju_calc(float $weight, int $height, int $age, string $gender, float $activityMultiplier): float
{
  $bmr = $gender === 'female'
    ? 10 * $weight + 6.25 * $height - 5 * $age - 161
    : 10 * $weight + 6.25 * $height - 5 * $age + 5;

  return round($bmr * $activityMultiplier, 0);
}

function kbju_get_activity_multiplier(string $level): float
{
  $map = [
    'sedentary'  => 1.2,   // минимальная
    'light'      => 1.375, // лёгкая
    'moderate'   => 1.55,  // средняя
    'active'     => 1.725, // высокая
    'very'       => 1.9,   // очень высокая
  ];
  return $map[$level] ?? 1.2;
}

function kbju_adjust_for_goal(float $calories, string $goal): float
{
  if ($goal === 'lose') return $calories * 0.85;
  if ($goal === 'gain') return $calories * 1.15;
  return $calories;
}

function kbju_split(float $calories, string $goal): array
{
  $proteinRatio = $goal === 'lose' ? 0.35 : ($goal === 'gain' ? 0.30 : 0.30);
  $fatRatio = 0.30;
  $carbRatio = 1 - $proteinRatio - $fatRatio;

  $pKcal = round($calories * $proteinRatio, 0);
  $fKcal = round($calories * $fatRatio, 0);
  $cKcal = round($calories * $carbRatio, 0);

  return [
    'calories' => round($calories, 0),
    'protein_kcal' => $pKcal,
    'fat_kcal' => $fKcal,
    'carbs_kcal' => $cKcal,
    'protein_g' => round($pKcal / 4, 0),
    'fat_g' => round($fKcal / 9, 0),
    'carbs_g' => round($cKcal / 4, 0),
  ];
}
