<?php
declare(strict_types=1);

/**
 * Расчёт TDEE по формуле Mifflin-St Jeor.
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
    'sedentary' => 1.2,
    'light' => 1.375,
    'moderate' => 1.55,
    'active' => 1.725,
    'very' => 1.9,
  ];
  return $map[$level] ?? 1.2;
}

function kbju_adjust_for_goal(float $calories, string $goal): float
{
  if ($goal === 'lose') return $calories * 0.85;
  if ($goal === 'gain') return $calories * 1.12;
  return $calories;
}

function kbju_split(float $calories, string $goal, float $weightKg = 0): array
{
  $weight = $weightKg > 0 ? $weightKg : 70;
  $proteinPerKg = $goal === 'lose' ? 2.0 : ($goal === 'gain' ? 1.8 : 1.6);
  $fatPerKg = $goal === 'gain' ? 1.0 : 0.9;

  $proteinG = max(40, round($weight * $proteinPerKg, 0));
  $fatG = max(35, round($weight * $fatPerKg, 0));

  $proteinKcal = $proteinG * 4;
  $fatKcal = $fatG * 9;
  $carbsKcal = max(0, round($calories - $proteinKcal - $fatKcal, 0));
  $carbsG = round($carbsKcal / 4, 0);

  return [
    'calories' => (int)round($calories, 0),
    'protein_kcal' => (int)$proteinKcal,
    'fat_kcal' => (int)$fatKcal,
    'carbs_kcal' => (int)$carbsKcal,
    'protein_g' => (int)$proteinG,
    'fat_g' => (int)$fatG,
    'carbs_g' => (int)$carbsG,
  ];
}

function kbju_targets_from_profile(array $profile): ?array
{
  $weight = (float)($profile['weight_kg'] ?? 0);
  $height = (int)($profile['height_cm'] ?? 0);
  $age = (int)($profile['age'] ?? 0);
  if ($weight <= 0 || $height <= 0 || $age <= 0) return null;

  $mult = kbju_get_activity_multiplier((string)($profile['activity_level'] ?? 'moderate'));
  $cal = kbju_adjust_for_goal(kbju_calc($weight, $height, $age, (string)($profile['gender'] ?? 'male'), $mult), (string)($profile['goal'] ?? 'maintain'));

  return kbju_split($cal, (string)($profile['goal'] ?? 'maintain'), $weight);
}
