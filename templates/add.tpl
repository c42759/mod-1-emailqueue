<div class="row">
	<div class="col-xs-12 col-sm-12">
		<form action="{c2r-bo-path}/{c2r-lg}/{c2r-module-folder}/add/" method="post">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<label for="slide-name">{c2r-lg-to}</label>
						<input type="text" class="form-control" name="to" value="" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div class="form-group">
						<label for="slide-name">{c2r-lg-subject}</label>
						<input type="text" class="form-control" name="subject" value="" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
					<div class="form-group">
						<label for="slide-name">{c2r-lg-priority}</label>
						<input type="number" class="form-control" name="priority" value="0" required>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="from-group">
						<label for="content">{c2r-lg-content}</label>
						<textarea class="form-control" rows="8" name="content"></textarea>
					</div>
					<div class="xs-spacer30 sm-spacer30"></div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12">
				<div class="sm-taright">
					<button type="submit" class="btn btn-success" name="save">Save</button>
				</div>
			</div>
		</form>
	</div>
</div>
