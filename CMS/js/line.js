function ClickMe(id)
{	
	var divId = id + '-div';
	if($('#' + id).prop('checked'))
	{
		$('#' + divId).show();
	}
	else
	{
		$('#' + divId).hide();
		$('#' + divId + ' input').each(function(){$(this).prop('checked', false);});
	}
}
function ClickMe2(id)
{	
	var divId = id.replace('-0', '-div');
	if($('#' + id).prop('checked'))
	{
		$('#' + divId + ' input').each(function(){$(this).prop('checked', true);});	
	}
	else
	{
		$('#' + divId + ' input').each(function(){$(this).prop('checked', false);});
	}
}
function SubmitForm()
{
	document.getElementById('form1').submit();
}
$(document).ready(function(){
	$('[name="lines[]"]').each(function(){
	})
	$('[name="line_stations[]"]').each(function(){
	})
});