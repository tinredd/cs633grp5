function updateToHr(field_name) {
	var label=$('#field option:selected').text();
	$('[name="subjecta"], [name="subject"]').val('Please update my '+label);
	$('[name="message"]').val('Please update my '+label+' to: [ENTER NEW VALUE HERE].\n\nYour assistance is very much appreciated!');
}

function checkAll (field_name) {
	var checkFlag=0;

	if (checkFlag==0) {
		$('[name="'+field_name+'[]"]').prop('checked',true);
		checkFlag=1;
	} else {
		$('[name="'+field_name+'[]"]').removeProp('checked');
		checkFlag=0;
	}
}

$(function () {
	$('input[type="date"]').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1972:2020"
	});

	$(document).on('click','.sort',function () {
		var tmp=$(this).attr('id').split('_');
		tmp.shift();
		var id=tmp.join('_');

		if (id==$('[name="sort"]').val() && $('[name="dir"]').val()=='ASC') $('[name="dir"]').val('DESC');
		else if (id==$('[name="sort"]').val() && $('[name="dir"]').val()=='DESC') $('[name="dir"]').val('ASC');
		else $('[name="dir"]').val('ASC');
		$('[name="sort"]').val(id).parents('form').eq(0).submit();
	});

	$(document).on('click','#newskill',function () {
		if ($('.newskill').length==0) {
			$newskillform=$('<div></div>');
			$newskillform.css({
				'margin-top':'2px'
			}).attr('class','newskill');

			$newskilllabel=$('<div></div>');
			if ($('.newskill').length==0) {
				$newskilllabel.html('Skill name').css({
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

			$newskilllink=$('<a></a>');
			$newskilllink.html('Add').addClass('button').css({
				'margin-left':'5px',
				'margin-top':'-10px'
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
					var url='/listeners/add_skill.php';

					$.each($('[name="skill_id[]"]'), function () {
						if ($(this).is(':checked')) skillsA.push($(this).val());
					});

					$.ajax({
						url: url,
						data: { 
							'skill_name': $skillname.val(),
							'checked_skill_id[]': skillsA
						},
						method: 'POST',
						success: function (data) {
							$('#allskills').html(data);
						},
						error: function () {

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