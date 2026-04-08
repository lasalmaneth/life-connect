<?php
$pageTitle = $pageTitle ?? 'LifeConnect — Register';
$pageKey = $pageKey ?? '';
$step = $step ?? 1;
$stepTotal = $stepTotal ?? 4;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/registration-split.css">
<script>window.ROOT = '<?= ROOT ?>';</script>
</head>
<body data-page="<?php echo htmlspecialchars($pageKey); ?>" data-step="<?php echo (int) $step; ?>" data-step-total="<?php echo (int) $stepTotal; ?>">
