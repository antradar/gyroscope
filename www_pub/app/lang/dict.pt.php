<?php

$dict_mons=array('','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez');
$dict_wdays=array('S','M','T','W','T','F','S');
$dict_dir='ltr';

$dict=array(
	'currency_separator_decimal'=>',',
	'currency_separator_thousands'=>'.',
	
	'login'=>'Entrar',
	'signin'=>'Entrar',
	'username'=>'Usuário',
	'password'=>'Senha',
	
	'dispname'=>'Nome Amigável',
	
	'powerbanner'=>'Esta aplicação é alimentada por Gyroscope %%version%%',
	'speech_clicktoactivate'=>'ativar reconhecimento de fala',
	
	'icon_settings'=>'Configurações',
	'icon_accounts'=>'Contas',
		
	'account_settings'=>'Account Settings',
	'signout'=>'Sign Out',
	'current_password'=>'Current Password',
	'new_password'=>'New Password',
	'repeat_password'=>'Confirm New Password',
	'change_password'=>'Update Password',
	'invalid_password'=>'invalid username or password',
	'mismatching_password'=>'new passwords mismatch',
	'must_provide_new_password'=>'you must specify a new password',
	'new_password_must_be_different'=>'new password must be different',
	'password_changed'=>'Password changed',
	'switch_user'=>'switch user',
	
	'yearmonth'=>'%%mon%% de %%year%%',
	
	'powered_by_'=>'Powered by %%power%%',
	'check_updates'=>'Check Updates',
	'lookup_closer'=>'close',
	'pickup_edit'=>'edit',
	

	'button_update'=>'Update',
	'button_delete'=>'Delete',
	'button_user_add'=>'Add User',
	'account_roles'=>'Roles',
	'account_login_reset'=>'force password reset upon login',
	'account_active'=>'active account',
	'account_virtual'=>'virtual account',
	
	'rights_accounts'=>'gerenciar usuários',
	'icon_reports'=>'Relatórios',

	'close_all_tabs'=>'Feche Tudo',

	'record_removed'=>'This record has been removed',
	'error_creating_record'=>'Error creating record',
	
	'icon_templatetypes'=>'Template Classes',
	'list_templatetype_stab'=>'Template Class',
	'button_templatetype_add'=>'Add Template Class',
	'list_templatetype_add_tab'=>'New Template Class',
	'list_templatetype_add'=>'add a template class',	
	'templatetype_label_templatetypename'=>'Template Class Name',
	'templatetype_label_templatetypekey'=>'Template Class Key',
	'templatetype_label_activetemplateid'=>'Active Template',

	'icon_systemplates'=>'System Templates',	
	'icon_templates'=>'Templates',
	'list_template_stab'=>'Template',
	'button_template_add'=>'Add Template',
	'list_template_add_tab'=>'New Template',
	'list_template_add'=>'add a template',	
	'template_label_templatename'=>'Template Name',
	'template_label_templatetext'=>'Content',
	
	'icon_reportsettings'=>'Report Settings',
	'list_reportsetting_stab'=>'Report Settings',
	'button_reportsetting_add'=>'Add Report Settings',
	'list_reportsetting_add_tab'=>'New Report Settings',
	'list_reportsetting_add'=>'add a report type',	
	'reportsetting_label_reportname'=>'Título',
	'reportsetting_label_reportgroup'=>'Nome do Grupo <em>(opcional)</em>',
	'reportsetting_label_reportfunc'=>'Custom Function <em>(add "return false;" to bypass default)</em>',
	'reportsetting_label_reportkey'=>'Chave do Relatório',
	'reportsetting_label_reportdesc'=>'Descrição',	
	
	'icon_sqlcompare'=>'Comparação de SQL',	
				
	'tab_welcome'=>'Bem Vindo',
	'hometab_welcome'=>'Bem-vindo ao Antradar Gyroscope',	
	'list_users'=>'Users',
	'list_user_add'=>'add a user',
	'list_user_add_tab'=>'New User',
	'csrf_expire'=>'You\'ve been on this screen for some time. For better security, please sign in again.'
	
);

$helpspots=array(
	'topicons'=>'<div class="tiptitle">Entry Icons</div>the icons on top of the screen are "entry points" to your application. when there are many icons, you may scroll through them.',
	'lookupview'=>'<div class="tiptitle">Lookup View</div>the results in this lookup panel are relevant to your input. often you can press Ctrl+Enter to auto select the top record.<br><br>if the label of the input field is blue, it links to the selected record.',
	'listviewpos'=>'<div class="tiptitle">List View</div>this is the list view for quickly locating records. record details are displayed in tabs.<br><br>the list and tab views are not synchronized; the list view is not a menu. knowing this helps you take take advantage of Gyroscope\'s flexible navigation.', 
	'listviewlookup'=>'<div class="tiptitle">Instant Lookup</div>start typing, the results will show up as you type',
	'fsview'=>'<div class="tiptitle">Full-Screen Mode</div>this is the full screen view. on a mobile device, the screen will stay on even if you have a screen lock enabled.',
	'tabview'=>'<div class="tiptitle">Tab View</div>this is a record detail view that\'s displayed in a tab. double click on the tab to refresh. on mobile devices use the reload link.',
	'maxtab'=>'<div class="tiptitle">Maximuze the Tab</div>press Ctrl,<,> at the same time to toggle maximized tab view.',
	'mysettings'=>'<div class="tiptitle">Personal Settings</div>you may change your account settings here, such as changing password or resetting the personalized help tips.',
	'richtexteditor'=>'<div class="tiptitle">Partial Editing</div>did you know that you can edit the source of selected content instead of the entire document?<br><br>highlight some text and then click the &lt;/&gt; button.',
	'templatevar'=>'<div class="tiptitle">Variable Lookup</div>template variables are inserted in the %%&mdash;%% format.<br><br>when the %%&mdash;%% text is selected in the editor, a reverse lookup is performed... you are welcome.',
);