<!-- paste:head -->
					<div class="formarea topform" id="savecrm">
						<form action="#action#" method="post" enctype="multipart/form-data">
<!-- cut:section -->
							<fieldset class="#class?#">
								<h5>#legend?#<span>#note?#</span></h5>
	<!-- cut:hidden -->
								<input type="hidden" value="#value#" name="#name#">
	<!-- /cut:hidden -->
	<!-- cut:buttons -->
								<div class="headarea">
		<!-- cut:button -->
									<a class="topbtn #class?#" href="#href#">#value#</a>
		<!-- /cut:button -->
		<!-- cut:text -->
									<p class="#class?#">#message#</p>
		<!-- /cut:text -->
								</div>
	<!-- /cut:buttons -->
	<!-- cut:honeypot -->
								<div class="formfield xdefault #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<textarea name="#name#" id="#name#" class="xfull" maxlength="#limit?#" data-stampte="#disabled?#">#value?#</textarea>
								</div>
	<!-- /cut:honeypot -->
	<!-- cut:checkbox -->
								<div class="formfield #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="checkbox" name="#name#" id="#name#" data-stampte="#checked?#" data-stampte="#disabled?#">
		<!-- paste:append -->
									<span class="fielderror">#error?#</span>
								</div>
	<!-- /cut:checkbox -->
	<!-- cut:radio -->
								<div class="formfield #class?#">
									<span>#title?#<em>#required?#</em></span>
		<!-- cut:button -->
									<div>
										<label for="#name#_#id#">#title?#</label>
										<input id="#name#_#id#" type="radio" name="#name#" value="#value#" data-stampte="#checked?#" data-stampte="#disabled?#">
			<!-- paste:append -->
									</div>
		<!-- /cut:button -->
									<span class="fielderror">#error?#</span>
								</div>
	<!-- /cut:radio -->
	<!-- cut:select -->
								<div class="formfield xdefault #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<select name="#name##multi?#" id="#name#" class="xfull" data-stampte="#disabled?#" data-stampte="#multiple?#">
		<!-- cut:option -->
										<option class="#class?#" value="#value?#" data-stampte="#selected?#" data-stampte="#disabled?#">#depth?##display#</option>
		<!-- /cut:option -->
									</select>
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function () {
	$('##name#').chosen();
})
									</script>
								</div>
	<!-- /cut:select -->
	<!-- cut:text -->
								<div class="formfield xdefault #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="#type#" name="#name#" id="#name#" class="xfull" value="#value?#" placeholder="#placeholder?#" maxlength="#length?#" data-stampte="#disabled?#">
									<span class="fielderror">#error?#</span>
								</div>
	<!-- /cut:text -->
	<!-- cut:number -->
								<div class="formfield xdefault #class?#">
									<input type="number" name="#name#" id="#name#" class="xfull" value="#value?#" placeholder="#placeholder?#" maxlength="#length?#" data-stampte="#disabled?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<span class="fielderror">#error?#</span>
								</div>
	<!-- /cut:number -->
	<!-- cut:textarea -->
								<div class="formfield xdefault #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<textarea name="#name#" id="#name#" class="xfull" maxlength="#limit?#" placeholder="#placeholder?#" data-stampte="#disabled?#">#value?#</textarea>
									<span class="limit limit_#name#">#limit?#</span>
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function(){
	if ( $('##name#').attr('maxlength') ) {
		$(".limit_#name#").text( $('##name#').attr('maxlength') - $('##name#').val().length );

		$("##name#").on('keyup.remaining', function () {
			$(".limit_#name#").text( $('##name#').attr('maxlength') - $('##name#').val().length );
		})
	};
});
									</script>
								</div>
	<!-- /cut:textarea -->
	<!-- cut:ckeditmax -->
								<div class="formfield xdefault #class?#">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<textarea name="#name#" id="#name#" class="ckeditmax" placeholder="#placeholder?#" data-stampte="#disabled?#">#value?#</textarea>
									<span class="fielderror">#error?#</span>
								</div>
	<!-- /cut:ckeditmax -->
	<!-- cut:date -->
								<div class="formfield xdefault #class?#" title="date"> 
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="text" name="#name#" id="#name#" class="xhalf" value="#value?#" placeholder="#placeholder?#" data-stampte="#disabled?#">
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function(){
	$('##name#').datepicker({
		monthNamesShort: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
		dateFormat: "yy-mm-dd",
		changeYear: true,
		changeMonth: true,
		yearRange: "c-90:c+10",
		<!-- paste:datepickerparam -->
	});
});
									</script>
								</div>
	<!-- /cut:date -->
	<!-- cut:dates -->
								<div class="formfield xdefault #class?#" title="dates">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="text" name="#name#" id="#name#" class="xhalf" value="#value?#" placeholder="#placeholder?#" data-stampte="#disabled?#">
									<input type="button" class="addfield" value="+" data-stampte="#disabled?#">
									<input type="button" class="subfield" value="-" #disabled?# data-stampte="#disabled?#">
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function(){
	$('##name#').datepicker({
		monthNamesShort: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
		dateFormat: "yy-mm-dd",
		changeYear: true,
		changeMonth: true,
		yearRange: "c-90:c+10",
		<!-- paste:datepickerparam -->
	});
});
									</script>
								</div>
	<!-- /cut:dates -->
	<!-- cut:dates_script -->
								<script type="text/javascript">
$(document).ready(function(){
	$(document).on('click.dates', '.addfield', function() {
		var time = new Date();

		var reference = '##name#_' + time.getTime();
		console.log(reference);
		var clone = $('.addfield').parent('div').first().clone();
		
		clone.children('label[for=#name#]').attr('for', clone.children('label[for=#name#]').attr('for') + '_' + time.getTime());
		clone.children('input[name=#name#]').attr('name', clone.children('input[name=#name#]').attr('name') + '_' + time.getTime());
		clone.children('input[id=#name#]').attr('id', clone.children('input[id=#name#]').attr('id') + '_' + time.getTime());
		
		clone.children(reference).removeClass('hasDatepicker');
		clone.children(reference).siblings(':disabled').attr('disabled', false);
		clone.children(reference).val('');

		$(this).parent('div[title=dates]').after(clone);
		$(reference).datepicker({
			monthNamesShort: [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ],
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			yearRange: "c-90:c+10",
			<!-- paste:datepickerparam -->
		});
	});
	$(document).on('click.dates', '.subfield', function() {
		console.log('click');
		$(this).parent('div[title=dates]').remove();
	});
});
								</script>
	<!-- /cut:dates_script -->
	<!-- cut:datetime -->
								<div class="formfield xdefault #class?#" title="times">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="text" name="#name#" id="#name#" class="xhalf" value="#value#" placeholder="#placeholder?#" data-stampte="#disabled?#">
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function() {
	$('##name#').datetimepicker({
		hourGrid: 6,
		minuteGrid: 15,
		stepMinute: 5,
		addSliderAccess: true,
		sliderAccessArgs: { touchonly: false },
		timeFormat: 'hh:mm TT'
	});
});
									</script>
								</div>
	<!-- /cut:datetime -->
	<!-- cut:time -->
								<div class="formfield xdefault #class?#" title="times">
									<label for="#name#">#title?#<em>#required?#</em></label>
									<input type="text" name="#name#" id="#name#" class="xhalf" value="#value#" placeholder="#placeholder?#" data-stampte="#disabled?#">
									<span class="fielderror">#error?#</span>
									<script type="text/javascript">
$(document).ready(function() {
	$('##name#').timepicker({
		hourGrid: 6,
		minuteGrid: 15,
		stepMinute: 5,
		addSliderAccess: true,
		sliderAccessArgs: { touchonly: false },
		timeFormat: 'hh:mm TT'
	});
});
									</script>
								</div>
	<!-- /cut:time -->
	<!-- cut:table -->
								<div class="listarea toplist #class?#">
									<table class="listtable">
										<thead>
											<tr>
		<!-- cut:head -->
												<th>#title?#</th>
		<!-- /cut:head -->
											</tr>
										</thead>
										<tbody>
		<!-- cut:row -->
											<tr class="#class?#">
			<!-- paste:icon -->
			<!-- cut:image -->
												<td class="#class?#"><img src="#src#" style="width: 100px; #style#"></td>
			<!-- /cut:image -->
			<!-- cut:column -->
												<td class="#class?#"><!-- paste:icon -->#value?#</td>
			<!-- /cut:column -->
			<!-- cut:options -->
												<td class="x80 center">
													<!-- paste:icon -->
				<!-- cut:create -->
													<a href="#href#">
														<img src="/images/icons/add.png" title="Add a child #noun?# to this tier" alt="Add"/>
													</a>
				<!-- /cut:create -->
				<!-- paste:create -->
				<!-- cut:zoom -->
													<a href="#href#"><img src="/images/icons/zoom.png" title="View this #noun?#" alt="View"/></a>
				<!-- /cut:zoom -->
				<!-- paste:zoom -->
				<!-- cut:edit -->
													<a href="#href#"><img src="/images/icons/pencil.png" title="Edit this #noun?#" alt="Edit"/></a>
				<!-- /cut:edit -->
				<!-- paste:edit -->
				<!-- cut:delete -->
													<a href="#href#" onclick="if(!confirm('Are you certain you wish to delete this #noun?#?')){ return false; }"><img src="/images/icons/delete.png" title="Delete this #noun?#" alt="Delete"/></a>
				<!-- /cut:delete -->
				<!-- paste:delete -->
												</td>
			<!-- /cut:options -->										
											</tr>
		<!-- /cut:row -->
										</tbody>
									</table>
								</div>
	<!-- /cut:table -->
	<!-- paste:upload -->
	<!-- paste:content -->

							</fieldset>
	<!-- cut:break -->
							<div class="clearfloat"></div>
	<!-- /cut:break -->
<!-- /cut:section -->
<!-- paste:tab -->
<!-- paste:accordion -->
<!-- paste:wrapper -->
							<hr class="clearfloat" />
							<fieldset class="formsubmit">
								<input type="submit" value="#submit#" class="submit button">
							</fieldset>
						</form>
						<div class="clearfloat"></div>
					</div>
