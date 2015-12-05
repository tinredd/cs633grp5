function updateToHr(field_name) {
	var label=$('#field option:selected').text();
	$('[name="subjecta"], [name="subject"]').val('Please update my '+label);
	$('[name="message"]').val('Please update my '+label+' to: [ENTER NEW VALUE HERE].\n\nYour assistance is very much appreciated!');
}

var checkFlag=true;
function checkAll (field_name) {
	$('[name="'+field_name+'"]').prop('checked',checkFlag);
	checkFlag=!checkFlag;
}

function textCheck(prefix,value,multiple) {
//	see if the value is currently in the list of already defined values
	var added=false; 
	var index=-1; 
	var v, tmp_selector;
	var classadd='';

	var name=prefix;
	if (multiple===undefined || multiple!==false) multiple=true;
	if (multiple===true) {
		name+='[]';
		classadd='mult';
	}

	var selector='[name="'+name+'"]';

	$.each($(selector),function () {
		v=$(this).val();
		if (v==value) {
			index=$(this).index(selector);
			added=true;
		}
	});

//	if it is, remove it ad deactivate the item...
	if (added) {
		if (multiple) $(selector).eq(index).remove();
		else $(selector).eq(index).val('');

		if (multiple) tmp_selector='#'+prefix+'_'+value;
		else tmp_selector='[id^="'+prefix+'_"]';

		$(tmp_selector).removeClass('specialselect'+classadd+'-selected').addClass('specialselect'+classadd);
	}
//	if not, add it and activate the item
	else {
		$input=$('<input />');
		$input.attr({
			'name':name,
			'type':'hidden'
		}).val(value);
		if (multiple) {

		} else {
			$(selector).remove();
			$('[id^="'+prefix+'_"]:not(#'+prefix+'_'+value+')').removeClass('specialselect'+classadd+'-selected').addClass('specialselect'+classadd);
		}
		$('#'+prefix+'_'+value).addClass('specialselect'+classadd+'-selected').removeClass('specialselect'+classadd).before($input);
	}
}

$(function () {
//	Datepicker
	$('.datepicker').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1972:2020"
	});

//	Pagination
	$(document).on('click','.page',function () {
		$('[name="pg"]').val($(this).text());
		$('[name="action"]').val('');

		$(this).parents('form').eq(0).submit();
	});

//	Logo redirect
	$(document).on('click','.logo',function () {
		window.location.href='/index.php';
	});

//	Textcheckbox
	$(document).on('click','.specialselectmult,.specialselectmult-selected',function() {
		var id=$(this).attr('id');
		var tmp=id.split('_');
		var value=tmp.pop();
		var prefix=tmp.join('_');

		textCheck(prefix,value,true);
	});

//	Textcheckbox
	$(document).on('click','.specialselect,.specialselect-selected',function() {
		var id=$(this).attr('id');
		var tmp=id.split('_');
		var value=tmp.pop();
		var prefix=tmp.join('_');

		textCheck(prefix,value,false);
	});

//	Table sorting functionality
	$(document).on('click','.sort',function () {
		var tmp=$(this).attr('id').split('_');
		tmp.shift();
		var id=tmp.join('_');

		if (id==$('[name="sort"]').val() && $('[name="dir"]').val()=='ASC') $('[name="dir"]').val('DESC');
		else if (id==$('[name="sort"]').val() && $('[name="dir"]').val()=='DESC') $('[name="dir"]').val('ASC');
		else $('[name="dir"]').val('ASC');
		$('[name="sort"]').val(id).parents('form').eq(0).submit();
	});

	$(document).on('click','.removeskill',function() {
		if (confirm('Are you sure you want to delete this skill?')) {
			var ind=$(this).attr('id').split('_')[1];
			$('#newskilltag_'+ind).remove();
			$('#myskill_'+ind).remove();
			$(this).remove();

			if ($('.tag').length==0) $('#noskills').html('(no skills defined)')
		}
	});

//	Adding an new skill to an employee as an HR
	$(document).on('click','#myskill',function () {
		if ($('.newskill').length==0) {
			$newskillform=$('<div></div>');
			$newskillform.css({
				'margin-top':'2px'
			}).attr('class','newskill');

			$newskilllabel=$('<div></div>');
			if ($('.newskill').length==0) {
				$newskilllabel.html('Skill name <span class="inactive italic">(select from list)</span>').css({
					'font-size':'0.8em',
					'margin-top':'10px'
				});
			}

			$newskillfield=$('<div></div>');

			$newskillinput=$('<input/>');
			$newskillinput.attr({
				'type':'text',
				'name':'skill_name'
			});

		//	Get the skills already selected so they can be ignored...
			var myentered=new Array();
			$.each($('.myskill'),function () {
				myentered.push($(this).val());
			});

			$newskillinput.autocomplete({
				source: function (request,response) {
					$.get('/listeners/add_emp_skill.php', {
							letter: $newskillinput.val(),
							entered: myentered
						},

						function (data) {
							response($.parseJSON(data));
						}
					);
				},
				select: function (event,ui) {
				//	Add a hidden field with the selected values...	
					$newmyskillinput=$('<input/>');
					$newmyskillinput.attr({
						'type':'hidden',
						'name':'skill_id[]'
					}).addClass('myskill').attr('id','myskill_'+(ui.item.value));

				//	Append a hidden input with a value of value field to the top...
					$newmyskillinput.val(ui.item.value);

				//	Add a "tag" with the skill name...
					$newskilltag=$('<div></div>');
					$newskillcontainertag=$('<div></div>');
					$newskilltag.html(ui.item.label).addClass('tag').addClass('newskilltag').attr('id','newskilltag_'+(ui.item.value));

					$newskilllink=$('<a></a>');
					$newskilllink.html('&times').addClass('button').addClass('removeskill').css({
						'margin':'0px 0 5px 5px'
					}).attr('id','newskilllink_'+(ui.item.value));

					$newskillcontainertag.append($newskilltag);
					$newskillcontainertag.append($newskilllink);
					$newskillcontainertag.append($newmyskillinput);
					$('#newskills').append($newskillcontainertag);
					$('#noskills').empty();

				//	Add the label to the autocomplete input...
					$(this).val(ui.item.label);
					$newskillinput.autocomplete('destroy');
					$newskillform.remove();
				}
			});

			$newskillfield.append($newskillinput);
			$newskillform.append($newskilllabel);
			$newskillform.append($newskillfield);
			$('#allmyskill').append($newskillform);
		} else {
			alert('Please add one skill at a time!');
		}
	});

//	Adding an new skill to a job as an HR rep
	$(document).on('click','#newskill, #newskillm',function () {
		var a=0;

		if ($(this).attr('id')=='newskill') a=1;
		else if ($(this).attr('id')=='newskillm') a=2;

		if ($('.newskill').length==0) {
			$newskillform=$('<div></div>');
			$newskillform.css({
				'float': 'left',
				'width': '100%',
				'margin-top': '10px'
			}).addClass('newskill');

			$newskilllabel=$('<div></div>');
			if ($('.newskill').length==0) {
				$newskilllabel.html('Skill name').css({
					'font-size': '0.7em',
					'text-transform': 'uppercase',
					'color': '#005F8C',
					'font-weight': 'bold'
				});
			}

			$newskillfield=$('<div></div>');

			$newskillinput=$('<input/>');
			$newskillinput.attr({
				'type':'text',
				'name':'skill_name'
			});

			$newskilllink=$('<a></a>');
			$newskilllink.html('Add').addClass('button').css({
				'margin-left':'5px',
			}).click(function() {
				$skillname=$(this).siblings('[name="skill_name"]').eq(0);
				$newskill=$(this).parents('.newskill').eq(0);

				if ($skillname.val().length==0) {
					$skillname.addClass('error');
				} else {
				//	Do AJAX to process the skill
					$newskill.remove();
					var skillsA=new Array();
					if ($skillname.hasClass('error')) $skillname.removeClass('error');

					var url='/listeners/add_job_skill.php';

					var name='';
					if (a==1) name='skill_id[]';
					else name='my_skill_id[]';

					$.each($('[name="'+name+'"]'), function () {
						skillsA.push($(this).val());
					});

					$.ajax({
						url: url,
						data: { 
							'a': a,
							'skill_name': $skillname.val(),
							'checked_skill_id': skillsA.join('|')
						},
						method: 'POST',
						success: function (data) {
							var out_id='';
							if (a==1) out_id='allskills';
							else out_id='myallskills';
							$('#'+out_id).html(data);
						},
						error: function (jqXHR, textStatus, errorThrown) {
						//	alert(jqXHR.status);
						}
					});
				}
			});

			$newskillfield.append($newskillinput);
			$newskillfield.append($newskilllink);
			$newskillform.append($newskilllabel);
			$newskillform.append($newskillfield);
			$('#skillfields').append($newskillform);
		} else {
			alert('Please add one skill at a time!');
		}
	});
});