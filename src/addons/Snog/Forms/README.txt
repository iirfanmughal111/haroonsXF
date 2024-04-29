/*======================================================================*\
|| #################################################################### ||
|| # Advanced Forms                                                   # ||
|| # ---------------------------------------------------------------- # ||
|| # Copyright © 2013-2019 Snog's Mods & Add-ons                      # ||
|| # https://snogssite.com                                            # ||
|| # All Rights Reserved.                                             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------------------------------------------------------- # ||
|| #################################################################### ||
\*======================================================================*/

/*======================================================================*\
|| License                                                              ||
\*======================================================================*/

Advanced Forms is Copyright © 2013-2019 Snog's Mods & Add-ons - All Rights Reserved.
You may not redistribute the package in whole or significant part.
You are allowed to run this package on one server, provided you have purchased the package from https://snogssite.com.
All other use or distribution is prohibited.


** BEFORE INSTALLATION **

Advanced Forms uses automated PCs for a large number of items.
Because a user can't send a PC to themself, it is strongly suggested that you create a special user that is used just for this purpose.
The most common user name to create is Forms System, but the name really doesn't matter so long as the name created does not ever need
to receive PCs from Advanced Forms.


** INSTALLATION **

To install Advanced Forms, upload the CONTENTS of the upload folder (not the upload folder itself) to your XenForo 2.0 site.
After uploading the files, go to your admin area and click on the Add-ons icon on the main admin page.
You should see Advanced Forms in the 'Installable add-ons' section. Click 'Install' in the Advanced Forms area.


** UPGRADING FROM ADVANCED APPLICATION FORMS FOR XenForo 1.x **

If you are upgrading from XenForo 1.x you need to be sure all promotion polls have ended and any decision promotions are completed before upgrading.
Existing polls and decision promotions are not included when upgrading and they will be lost.

You MUST have had at least version 1.2.13 of Advanced Application Forms installed on your XenForo 1.x site before upgrading to
XenForo 2.x. Earlier versions have not been tested with upgrading and may fail.

Also since there have been some major improvements in Advanced Forms for XenForo 2.x, it is is not possible to import forms from 
the export XML produced by Advanced Application Forms for XenForo 1.x. Because of that, it is important that your snog_applications_* 
tables exist on the site you are upgrading (there should be 6 tables starting with snog_applications). They will be automatically 
upgraded when installing Advanced Forms for XenForo 2.x.


** SAMPLE FORMS **

Sample forms are included in the do_not_upload folder. After installation, go to your admin area and select Advanced Forms.
Then select Import from the menu and select the forms-export.xml in the do_not_upload/SampleForms folder. Then click Proceed to 
import the sample forms.

Since any user names set in the forms most likely don't exist on your site, after importing it is important that you edit the form
(admin-Advanced Forms->Forms) and change those user names to names that exist on your site. Also check the criteria for the form to
be sure users can match the criteria set for the sample form.

The sample form is also tied to a sample TYPE. Edit the TYPE criteria (admin->Advanced Forms->Types) to be sure users can access the
TYPE on your site. Either that, or remove the Form type from the sample form itself.

If the form has a Form type (which the sample form normally does) and if a user does not match both the FORM criteria AND the TYPE
criteria, the user will be unable to view the sample form.
They will receive this message: "You do not meet the criteria for any forms. No forms are available for you."


** ANSWER DATABASE TABLE **

There is an option to save answers for a form in the xf_snog_forms_answers table. Under normal circumstances this should not be used.

However, there are some people that may want to use this option to build their own statistics. No support will be given for this option
beyond what is in this file.

   - Except for file upload and header types, all answers are saved in the table
   - posid = form ID number
   - questionid = question ID number
   - answer_date = the Unix timestamp of when the form was filled out
   - user_id = the user ID of the person filling out the form
   - answer = the actual answer to the question

NOTE: If a question is deleted from a form, all answers to that question are also deleted.
      If a form is deleted, all answers for that form are also deleted.

I'm going to be very blunt about this. If you can't figure out how to extract the data you want, then you probably shouldn't
be using this option. I will not answer questons about how to extract information from the database.