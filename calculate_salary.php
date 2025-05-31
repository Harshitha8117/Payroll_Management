<?php
class SalaryRulesEngine {
    private $baseSalary, $tenureYears, $performanceScore, $overtimeHours, $shiftType, $unpaidLeaveDays, $currencyRate;
    public function __construct($baseSalary, $tenureYears, $performanceScore = 0, $overtimeHours = 0, $shiftType = 'day', $unpaidLeaveDays = 0, $currencyRate = 1) {
        $this->baseSalary = $baseSalary;
        $this->tenureYears = $tenureYears;
        $this->performanceScore = $performanceScore;
        $this->overtimeHours = $overtimeHours;
        $this->shiftType = $shiftType;
        $this->unpaidLeaveDays = $unpaidLeaveDays;
        $this->currencyRate = $currencyRate;
    }
    public function calculate() {
        $components = [];
        $workingDays = 22;
        $baseAfterLeave = $this->baseSalary * max(0, ($workingDays - $this->unpaidLeaveDays) / $workingDays);
        $components['Base Salary'] = $baseAfterLeave;
        $housingPercent = $this->tenureYears > 2 ? 0.20 : 0.10;
        $components['Housing Allowance'] = $baseAfterLeave * $housingPercent;
        $components['Performance Bonus'] = $this->performanceScore * 0.15 * $baseAfterLeave;
        $hourlyRate = $this->baseSalary / 160;
        $overtimeRateMultiplier = 1.5;
        $components['Overtime Pay'] = $this->overtimeHours * $hourlyRate * $overtimeRateMultiplier;
        $shiftDifferentials = ['day' => 1, 'night' => 1.2, 'weekend' => 1.5];
        $shiftMultiplier = $shiftDifferentials[$this->shiftType] ?? 1;
        $components['Shift Differential'] = ($baseAfterLeave * 0.10) * ($shiftMultiplier - 1);
        $taxableIncome = array_sum($components);
        if ($taxableIncome <= 3000) {
            $tax = $taxableIncome * 0.05;
        } elseif ($taxableIncome <= 6000) {
            $tax = 150 + ($taxableIncome - 3000) * 0.10;
        } else {
            $tax = 450 + ($taxableIncome - 6000) * 0.20;
        }
        $components['Tax'] = -$tax;
        $components['Net Pay'] = array_sum($components);
        if ($this->currencyRate != 1) {
            foreach ($components as $key => $val) {
                $components[$key] = round($val * $this->currencyRate, 2);
            }
        }
        return $components;
    }
}

// INR formatter helper
function formatINR($number) {
    $decimal = number_format($number - floor($number), 2, '.', '');
    $number = floor($number);
    $numStr = (string)$number;
    $lastThree = substr($numStr, -3);
    $restUnits = substr($numStr, 0, -3);
    if ($restUnits != '') {
        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
        $formatted = $restUnits . "," . $lastThree . $decimal;
    } else {
        $formatted = $lastThree . $decimal;
    }
    return "₹" . $formatted;
}

$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $engine = new SalaryRulesEngine(
        floatval($_POST['baseSalary']),
        floatval($_POST['tenureYears']),
        floatval($_POST['performanceScore']),
        floatval($_POST['overtimeHours']),
        $_POST['shiftType'],
        floatval($_POST['unpaidLeaveDays']),
        floatval($_POST['currencyRate'])
    );
    $result = $engine->calculate();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Advanced Salary Calculator</title>
<style>
  body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 20px; max-width: 600px; margin: auto; }
  h1 { text-align: center; }
  form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
  label { display: block; margin: 15px 0 5px; font-weight: bold; }
  input, select { width: 100%; padding: 8px; font-size: 1em; }
  button { margin-top: 20px; padding: 10px; width: 100%; background: #007BFF; color: white; font-size: 1.1em; border: none; border-radius: 5px; cursor: pointer; }
  button:hover { background: #0056b3; }
  .result { margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
  .result h2 { margin-top: 0; }
  .result table { width: 100%; border-collapse: collapse; }
  .result th, .result td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
  .negative { color: red; }
  .netpay { font-weight: bold; font-size: 1.2em; }
</style>
</head>
<body>

<h1>Advanced Salary Calculator</h1>
<form method="POST" action="">
    <label for="baseSalary">Base Salary (Rupees)</label>
    <input type="number" step="0.01" id="baseSalary" name="baseSalary" required value="<?= $_POST['baseSalary'] ?? '4000' ?>">

    <label for="tenureYears">Tenure (years)</label>
    <input type="number" step="0.01" id="tenureYears" name="tenureYears" required value="<?= $_POST['tenureYears'] ?? '3' ?>">

    <label for="performanceScore">Performance Score (0 to 1)</label>
    <input type="number" step="0.01" min="0" max="1" id="performanceScore" name="performanceScore" required value="<?= $_POST['performanceScore'] ?? '0.85' ?>">

    <label for="overtimeHours">Overtime Hours</label>
    <input type="number" step="0.1" id="overtimeHours" name="overtimeHours" required value="<?= $_POST['overtimeHours'] ?? '10' ?>">

    <label for="shiftType">Shift Type</label>
    <select id="shiftType" name="shiftType" required>
        <?php
        $shifts = ['day', 'night', 'weekend'];
        $selectedShift = $_POST['shiftType'] ?? 'night';
        foreach ($shifts as $shift) {
            $sel = ($shift === $selectedShift) ? 'selected' : '';
            echo "<option value='$shift' $sel>" . ucfirst($shift) . "</option>";
        }
        ?>
    </select>

    <label for="unpaidLeaveDays">Unpaid Leave Days (monthly)</label>
    <input type="number" step="0.01" id="unpaidLeaveDays" name="unpaidLeaveDays" required value="<?= $_POST['unpaidLeaveDays'] ?? '1' ?>">

    <label for="currencyRate">Currency Conversion Rate (1 if none)</label>
    <input type="number" step="0.0001" id="currencyRate" name="currencyRate" required value="<?= $_POST['currencyRate'] ?? '1' ?>">

    <button type="submit">Calculate Salary</button>
</form>

<?php if ($result): ?>
  <div class="result">
    <h2>Salary Breakdown</h2>
    <table>
      <?php foreach ($result as $key => $value): ?>
        <tr>
          <th><?= htmlspecialchars($key) ?></th>
          <td class="<?= $value < 0 ? 'negative' : ($key === 'Net Pay' ? 'netpay' : '') ?>">
            <?= formatINR($value) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
<?php endif; ?>

</body>
</html>
