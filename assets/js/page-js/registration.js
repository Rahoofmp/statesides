function disableButton(){$('#register').hide()}
function enableButton(){$('#register').show()}
function trim(a){return a.replace(/^\s+|\s+$/,'')}
function checkStep(a,b){if(a==1){enableButton()}else{disableButton()}}
function checkStepSponsor(a,b){if(a==1){}else{disableButton()}}
$(document).ready(function(){var rootPath=$("#rootPath").val();var sponsorCheck=0;$("#sponsor_username").blur(function(){var validationError=0;var sponsorName=$('#sponsor_username').val();if(validationError!=1){var checkUserAvailable=rootPath+"signup/checkSponsorAvailability";$("#spinner_sponsor").addClass("fa-spin fa-cog blue-color");$("#message_box").removeClass();$("#message_box").addClass('messagebox');$("#message_box").html(checking_sponser_user_name).show().fadeTo(100,1);$("#sponsor_username").attr("disabled",!0);$.post(checkUserAvailable,{username:$('#sponsor_username').val()},function(data){if(trim(data)=='no'){$("#message_box").fadeTo(100,0.1,function(){$("#spinner_sponsor").removeClass("fa-spin fa-cog");var error="<span class='help-block'><i class='fa fa-times fa-lg'></i> "+invalid_sponser_user_name+"</span>";$(this).html(error).show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');$("#sponsor_username").attr("disabled",!1);sponsorCheck=0;checkStepSponsor(sponsorCheck)})}else{$("#message_box").fadeTo(100,0.1,function(){$("#spinner_sponsor").removeClass("fa-spin fa-cog");$(this).html('<span class="help-block-sucess"> <i class="symbol ok">'+sponser_name_validated+' </i></span>').show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');$("#sponsor_username").attr("disabled",!1);sponsorCheck=1;checkStepSponsor(sponsorCheck)})}})}});$("#username").blur(function(){var validationError=0;var username=$('#username').val();if(validationError!=1){var checkUserAvailable=rootPath+"signup/checkUsernameAvailability";$("#spinner_sponsor").addClass("fa-spin fa-cog blue-color");$("#message_box_user").removeClass();$("#message_box_user").addClass('messagebox');$("#message_box_user").html(checking_user_name).show().fadeTo(100,1);$("#username").attr("disabled",!0);$.post(checkUserAvailable,{username:$('#username').val()},function(data){if(trim(data)=='yes'){$("#message_box_user").fadeTo(100,0.1,function(){$("#spinner_sponsor").removeClass("fa-spin fa-cog");var error="<span class='help-block'><i class='fa fa-times fa-lg'></i> "+invalid_user_name+"</span>";$(this).html(error).show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');$("#username").attr("disabled",!1);userCheck=0;checkStep(userCheck)})}else{if(username==""){$("#message_box_user").fadeTo(100,0.1,function(){$("#spinner_sponsor").removeClass("fa-spin fa-cog");var error="<span class='help-block'><i class='fa fa-times fa-lg'></i> "+invalid_user_name+"</span>";$(this).html(error).show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');$("#username").attr("disabled",!1);userCheck=0;checkStep(userCheck)})}else{$("#message_box_user").fadeTo(100,0.1,function(){$("#spinner_sponsor").removeClass("fa-spin fa-cog");$(this).html('<span class="help-block-sucess"> <i class="symbol ok">'+user_name_validated+' </i></span>').show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');$("#username").attr("disabled",!1);userCheck=1;checkStep(userCheck)})}}})}})});$("#user_name_wallet").blur(function(){var rootPath=$("#rootPath").val();var validationError=0;if($("#user_name_wallet").val()==''){validationError=1;$("#error_wallet_username").fadeTo(100,0.1,function(){$("#spinner_wallet_username").removeClass("fa-spin fa-cog");$(this).html('<span class="help-block"><i class="fa fa-times fa-lg"></i> '+user_name_cannot_be_null+' </span>').show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required')})}
if(validationError!=1){var userNameAvailability=rootPath+"register/checkUsernameAvailability"
$("#spinner_wallet_username").addClass("fa-spin fa-cog");$("#error_wallet_username").html(checking_username_availability).show().fadeTo(100,1);$("#error_wallet_username").closest('.form-group').removeClass('has-success').removeClass('has-error').find('.symbol').removeClass('ok')
$.post(userNameAvailability,{username:$('#user_name_wallet').val()},function(data){if(trim(data)=='yes'){$("#error_wallet_username").fadeTo(100,0.1,function(){$("#spinner_wallet_username").removeClass("fa-spin fa-cog");$(this).html('<span class="help-block"><i class="symbol ok"></i> '+user_name_available+' </span>').show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok')})}else if(trim(data)=='no'){$("#error_wallet_username").fadeTo(100,0.1,function(){$("#spinner_wallet_username").removeClass("fa-spin fa-cog");$(this).html('<span class="help-block"> <i class="fa fa-times fa-lg">'+user_name_not_available+'</i> </span>').show().fadeTo(100,1);$(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required')})}})}})