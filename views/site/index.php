<?php

/* @var $this yii\web\View */

$this->title = 'Raccoon War Room';

?>
<br/><br/>
<div class="site-index">
	<div class="panel panel-info">
			<div class="panel-heading">
		    	<h3 class="panel-title">Symbols</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Symbol</th>
							<th>Last</th>
							<th>Current</th>
							<th>Close Compare Last</th>
							<th>Close Compare Current</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($symbols as $symbol) : ?>
						<tr>
							<?php 
								$lastText = "Not Found";
								$classLast = "";
								$currentText = "Not Found";
								$classCurrent = "";

								//Current Value
								if (isset($currents[$symbol->symbol]))
								{
									$current = $currents[$symbol->symbol];

									if ($current->close >= $current->open)
									{
										$currentText = "UP";

										if ($current->open == $current->low)
										{
											$currentText = "Strong ".$currentText;
										}
										else
										{
											$currentText = "Sideway ".$currentText;
										}

										$classCurrent = "success";
									}
									else
									{
										$currentText = "DOWN";

										if ($current->open == $current->high)
										{
											$currentText = "Strong ".$currentText;
										}
										else
										{
											$currentText = "Sideway ".$currentText;
										}
										$classCurrent = "danger";
									}
								}

								//Last Value
								if (isset($lasts[$symbol->symbol]))
								{
									$last = $lasts[$symbol->symbol];

									if ($last->close >= $last->open)
									{
										$lastText = "UP";

										if ($last->open == $last->low)
										{
											$lastText = "Strong ".$lastText;
										}
										else
										{
											$lastText = "Sideway ".$lastText;
										}

										$classLast = "success";
									}
									else
									{
										$lastText = "DOWN";

										if ($last->open == $last->high)
										{
											$lastText = "Strong ".$lastText;
										}
										else
										{
											$lastText = "Sideway ".$lastText;
										}

										$classLast = "danger";
									}
								}

							?>
							<td><?= $symbol->symbol ?></td>
							<td class="<?= $classLast ?>"><?= $lastText ?></td>
							<td class="<?= $classCurrent ?>"><?= $currentText ?></td>
							<td><input type="checkbox" data-update="last" data-state="<?= $currents[$symbol->symbol]->receive_message ?>" data-id="<?= $currents[$symbol->symbol]->id ?>" data-off-color="default" data-size="mini" data-on-color="default" name="last-<?= strtolower($symbol->symbol) ?>" checked></td>
							<td><input type="checkbox" data-update="current" data-state="<?= $currents[$symbol->symbol]->receive_current ?>" data-id="<?= $currents[$symbol->symbol]->id ?>" data-off-color="default" data-size="mini" data-on-color="default" name="current-<?= strtolower($symbol->symbol) ?>" checked></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$("input[type='checkbox']").each(function(index) {
			var state = $(this).data("state") == 1 ? true : false;
			$(this).bootstrapSwitch('state', state, false);
			$(this).on('switchChange.bootstrapSwitch', function(event, state) {
				$.post( "<?= Yii::$app->urlManager->createUrl(["site/symbol"]) ?>", { id: $(this).data("id"), update_state: $(this).data("update")})
					.done(function( data ) {
				});
			});	
		});
	});

</script>