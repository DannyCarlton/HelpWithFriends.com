{include file="header.tpl" }

<style>
#available-s {
	text-transform:uppercase;
}

#available	{
	text-transform:uppercase;
}
</style>
			
			
			<!-- Inner Content -->
			<div class="inner-page padd">
			
				<!-- Showcase Start -->
				
				<div class="showcase">
					<div class="container">
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<!-- Showcase section item -->
								<div class="showcase-item">
									<!-- Image -->
									<img id="simple-find-logo" class="img-responsive showcase-img" src="img/tile-s.png" alt="" style="max-width:25%" />
									<!-- Heading -->
									<h3>Simple <small style="font-size:12px;display:block;line-height:120%;margin-top:-10px"><br>Enter tiles/letters. You may use up to two "?" as wildcards.</small></h3>
									<!-- Paragraph -->
										<form action="/#results" method="POST" class="form-horizontal col-xs-12 col-sm-12 col-md-10" role="form" style="float:right;padding-left:10px;">
											<input type="hidden" name="find" value="">
											<div class="form-group" style="margin-bottom:5px">
												<label for="inputName" class="col-md-3 control-label" style="padding-top:0;padding-right:0">Letters Available</label>
												<div class="col-md-8 text-right">
													<input type="text" class="form-control" name="available" id="available-s" value="{$available}" placeholder="Your Letters" maxlength="9">
												</div>
											</div>    
											<div class="form-group">
												<div class="col-md-offset-2 col-md-9 text-right">
													<button type="submit" class="btn btn-danger btn-sm" >Search...</button>
												</div>
											</div>
										</form>
										<div style="clear:both"></div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6">
								<!-- Showcase section item -->
								<div class="showcase-item">
									<!-- Image -->
									<img class="img-responsive showcase-img" src="img/tile-a.png" alt="" style="max-width:25%" />
									<!-- Heading -->
									<h3>Advanced <small style="font-size:12px;display:block;line-height:120%;margin-top:-10px"><br>Enter tiles/letters. You may use up to two "?" as wildcards.</small></h3>
									<!-- Paragraph -->
										<form action="/#results" method="POST" class="form-horizontal col-xs-12 col-md-10" role="form" style="float:right;padding-left:10px;">
											<div class="form-group">
												<label for="available" class="col-md-3 control-label" style="padding-top:0;padding-right:0">Letters Available</label>
												<div class="col-md-8">
													<input type="text" class="form-control" name="available" id="available" value="{$available}" placeholder="Your Letters" maxlength="9">
												</div>
											</div>
											<div class="form-group">
												<label for="range" class="col-md-3 control-label" style="padding-right:0">Word Length Range</label>
												<div class="col-md-8">
													<input type="text" class="form-control" name="range" id="range" 
														{if $range}value="{$range}"{else}placeholder="Examples: 5 or 2-4, default is 2-15"{/if}>
												</div>
											</div>
											<div class="form-group" style="margin-bottom:0px">
												<label for="find" class="col-md-3 control-label" style="padding-right:0">Start/End</label>
												<div class="col-md-8" >
													<input type="text" class="form-control" name="start" id="start" value="{$start}" placeholder="Starts with..." style="width:45%;float:left" maxlength="14">
													<input type="text" class="form-control" name="end" id="end" value="{$end}" placeholder="Ends with..." style="width:45%;float:right" maxlength="14">
												</div>
											</div>    
											<div class="form-group" style="margin-top:10px">
												<div class="col-md-offset-2 col-md-9 text-right">
													<button type="submit" class="btn btn-danger btn-sm" >Search...</button>
												</div>
											</div>
										</form>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="results" class="showcase" style="margin-top:0;padding-top:0">
					<div class="container">
						<div class="row">
							<div class="col-sm-12 col-md-12" style="display:{$results_display};">
								<!-- Showcase section item -->
									{$results}
							</div>
						</div>
					</div>
				</div>
				
				<!-- Showcase End -->
				
			</div><!-- / Inner Page Content End -->	


			
		

{include file="footer.tpl"}
