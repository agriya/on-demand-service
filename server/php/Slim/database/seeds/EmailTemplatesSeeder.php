<?php

use Phinx\Seed\AbstractSeed;

class EmailTemplatesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
   public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Forgot Password',
                'subject' => 'Forgot Password',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">A password request has been made for your user account at ##SITE_NAME##.</p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td>New password: </td>
										   <td>##PASSWORD##</td> 
									   </tr></p>
									   <tr></tr>
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             If you did not request this action and feel this is in error, please contact us at ##CONTACT_MAIL##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>
',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user submit the forgot password form.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Activation Request',
                'subject' => 'Please activate your ##SITE_NAME## account',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Welcome @ ##SITE_NAME##!. Your account has been created.</p>
									<p class="dc clsPreHead textb">Please visit the following URL to activate your account:<br></p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td colspan="2">##ACTIVATION_URL## </td>
									   </tr></p>
									   <tr></tr>
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             If you did not request this action and feel this is in error, please contact us at ##CONTACT_MAIL##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user registering an account he/she will get an activation request.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Welcome Email',
                'subject' => 'Welcome to ##SITE_NAME##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">We wish to say a quick hello and thanks for registering at ##SITE_NAME##..</p>
                                    <table border="0" width="100%">
									   <tr></tr>
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             If you did not request this action and feel this is in error, please contact us at ##CONTACT_MAIL##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user register in this site and get activate',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'New User Join',
                'subject' => 'New user joined in ##SITE_NAME## account',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'#ffffff\'\', endColorstr=\'\'#f2f2f2\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">A new user named "##USERNAME##" has joined in ##SITE_NAME## account.<br></p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td>Username: </td>
										   <td>##USERNAME##</td> 
									   </tr>
									   <tr>
										   <td >Email: </td>
										   <td>##EMAIL##</td> 
									   </tr>
									   </p>
									   <tr></tr>                                           
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail to admin, when a new user registered in the site. For this you have to enable "admin mail after register" in the settings page.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Admin User Add',
                'subject' => 'Welcome to ##SITE_NAME##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">##SITE_NAME## team added you as a user in ##SITE_NAME##.<br></p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
									   <tr>
										   <td colspan="2">Your account details.</td> 
									   </tr>
										<tr>
										   <td>##LOGINLABEL##:</td>
										   <td>##USEDTOLOGIN##</td> 
									   </tr>
									   <tr>
										   <td>Password: </td>
										   <td>##PASSWORD##</td> 
									   </tr>
									   </p>
									   <tr></tr>                                           
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail to user, when a admin add a new user.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]     ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Admin User Active',
                'subject' => 'Your ##SITE_NAME## account has been activated',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Your account has been activated.<br></p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'We will send this mail to user, when user active by administrator.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]       ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Admin User Deactivate',
                'subject' => 'Your ##SITE_NAME## account has been deactivated',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Your ##SITE_NAME## account has been deactivated.<br></p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'We will send this mail to user, when user active by administrator.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]       ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Admin User Delete',
                'subject' => 'Your ##SITE_NAME## account has been removed',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Your ##SITE_NAME## account has been removed.<br></p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'We will send this mail to user, when user delete by administrator.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]     ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Admin Change Password',
                'subject' => 'Password changed',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Admin reset your password for your  ##SITE_NAME## account.<br></p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td>Your new password:</td>
										   <td>##PASSWORD##</td> 
									   </tr>
									   </p>
									   <tr></tr>                                           
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail to user, when admin change user\'s password.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]     ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Contact Us',
                'subject' => '[##SITE_NAME##] ##SUBJECT##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear Admin,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">##MESSAGE##<br></p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td>Telephone:</td>
										   <td>##TELEPHONE##</td> 
									   </tr>
									   <tr>
										   <td>IP:</td>
										   <td>##IP##</td> 
									   </tr>
									   <tr>
										   <td>Whois:</td>
										   <td>http://whois.sc/##IP##</td> 
									   </tr>
									   <tr>
										   <td>URL:</td>
										   <td>##FROM_URL##</td> 
									   </tr>
									   </p>
									   <tr></tr>                                           
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'We will send this mail to admin, when user submit any contact form.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Contact Us Auto Reply',
                'subject' => '[##SITE_NAME##] RE: ##SUBJECT##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">Thanks for contacting us. We\'ll get back to you shortly.<br></p>
                                    <p class="dc clsPreHead textb">Please do not reply to this automated response. If you have not contacted us and if you feel this is an error, please contact us through our site ##CONTACT_URL##<br></p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
								 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">------ you wrote -----<br></p>
                                    <p class="dc clsPreHead textb">##MESSAGE##<br></p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##CONTACT_FROM_EMAIL##',
                'info' => 'we will send this mail to user, when user submit the contact us form.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Invite Friends',
                'subject' => 'Invitation by  ##INVITED_USER_NAME##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'#ffffff\'\', endColorstr=\'\'#f2f2f2\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    ##MESSAGE##
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  Use my personal link to get the credit: ##INVITE_URL##
                  <br/><br/><br/>
                  Regards, <br/>
                  ##INVITED_USER_NAME##

                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user invites',
                'reply_to' => '##REPLY_TO_EMAIL##',
                'plugin' => 'Referral/Referral'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'New Message',
                'subject' => '##USERNAME## sent you a message on ##SITE_NAME##...',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'#ffffff\'\', endColorstr=\'\'#f2f2f2\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Dear ##OTHERUSERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">##USERNAME## sent you a message in ##SITE_NAME##</p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
                                       	<tr>
										   <td>Type of Service: ##SERVICENAME##</td> 
									   </tr>
                                        <tr>
										   <td>Booking for: ##APPOINTMENT_DATE##</td> 
									   </tr>
										<tr>
										   <td>##MESSAGE##</td> 
									   </tr>
										<tr>
										   <td>##MESSAGE_LINK##</td> 
									   </tr>                                       
                                       </p>
									   <tr></tr>                                           
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when a user get new message',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Pre Approved Enquiry',
                'subject' => '[##SITE_NAME##] ##SERVICE_PROVIDER## pre-approved your enquiry request',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##REQUESTOR_NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##SERVICE_PROVIDER## pre-approved your enquiry request.</p>
                                    <table border="0" width="100%">
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             Please follow this link to response ##LINK##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service provider pre-approved the enquiry.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'New Service Request',
                'subject' => '[##SITE_NAME##] New service request received',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##SERVICE_PROVIDER##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> You have received new service request.</p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
										   <td>Requestor: </td>
										   <td>##REQUESTOR_NAME##</td> 
									   </tr>
                                       <tr>
										   <td>Appointment Date : </td>
										   <td>##DATE##</td> 
									   </tr></p>
									   <tr></tr>
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             Please follow this link to response ##LINK##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user submit new service request.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Accept',
                'subject' => '[##SITE_NAME##] Your service request accepted by ##SERVICE_PROVIDER##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##REQUESTOR_NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> Your service request accepted by ##SERVICE_PROVIDER##.</p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
                                       <tr>
										   <td>Appointment Date : </td>
										   <td>##DATE##</td> 
									   </tr></p>
									   <tr></tr>
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             Please follow this link to response ##LINK##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service Provider Accept the Request.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Reject',
                'subject' => '[##SITE_NAME##] ##SERVICE_PROVIDER## not able accept your service request',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##REQUESTOR_NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##SERVICE_PROVIDER## not able accept your service request.</p>
                                    <table border="0" width="100%">
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             Please find new service provider for your requirement ##LINK##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service provider reject the request.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Complete',
                'subject' => '[##SITE_NAME##] ##SERVICE_PROVIDER## marked as completed the work',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##REQUESTOR_NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##SERVICE_PROVIDER## marked as completed the work.</p>
                                    <table border="0" width="100%">
                                       <tr>
                                          <td colspan="2" style="padding:30px 5px;"> 
                                             For accept/reject the work completion, please follow ##LINK##. 
                                          </td>
                                       </tr>                                             
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service request moved to complete status.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Closed',
                'subject' => '[##SITE_NAME##] ##REQUESTOR_NAME## marked as closed the work',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##SERVICE_PROVIDER##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##REQUESTOR_NAME## marked as closed the work.</p>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service user closed.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Cancelled',
                'subject' => '[##SITE_NAME##] ##REQUESTOR_NAME## cancelled the booking',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##SERVICE_PROVIDER##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##REQUESTOR_NAME## cancelled the booking.</p>
                                    <table border="0" width="100%">
                                       <p class="dc clsPreHead textb">
										<tr>
                                           <td>Appointment Date:</td>
										   <td>##DATE##</td> 
									   </tr></p>                                          
                                    </table>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when user cancelled the service.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ] ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Service Request Expired',
                'subject' => '[##SITE_NAME##] Your booking request is expired',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##REQUESTOR_NAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb"> ##SERVICE_PROVIDER## still not respond for your booking for ##APPOINTMENT_FROM_DATE##. So system will expired the booking and amount is revised. Please find other service provider book it.</p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service request moved to Expired status.',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]   ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'Review - Remainder Notification',
                'subject' => '[##SITE_NAME##] Please give your rating and review about ##FIRSTNAME## ##LASTNAME##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">##FIRSTNAME## ##LASTNAME## completed the ##SERVICENAME##. Please give your rating and review about  ##FIRSTNAME## ##LASTNAME##.</p>
									<p class="dc clsPreHead textb">POST your rating and review: ##SERVICEURL##</p>
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'we will send this mail, when service nor reviewd',
                'reply_to' => '##REPLY_TO_EMAIL##'
            ]  ,
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'name' => 'New Interest Received',
                'subject' => '##SITE_NAME## ##SERVICE_PROVIDER_FIRSTNAME## ##SERVICE_PROVIDER_LASTNAME## made interest to work ##SERVICENAME##',
                'body_content' => '<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
<div style="margin: 5px 0pt; padding: 20px; width: 700px; font-family: Open Sans,sans-serif; background-color: #f2f2f2; background-repeat: no-repeat;-webkit-border-radius: 10px;
   -moz-border-radius: 10px;
   border-radius: 10px;">
   <div style="border-bottom: 1px solid #ccc; margin: 0pt; background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -o-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      background: -ms-linear-gradient(top, #ffffff 0%,#f2f2f2 100%);
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'\'\'\'#ffffff\'\'\'\', endColorstr=\'\'\'\'#f2f2f2\'\'\'\',GradientType=0 ); 
      min-height: 70px;">
      <table cellspacing="0" cellpadding="0" width="700">
         <tbody>
            <tr>
               <td  valign="top" style="padding:14px 0 0 10px; width: 110px; min-height: 37px;">
                  <a style="color: #0981be;" title="##SITE_NAME##" href="#" target="_blank"></a>
                  <a style="color: #0981be;" title="##SITE_NAME##" href="##SITE_URL##" target="_blank">
                  <img style="padding-right: 15px; border: 0px 1px 0px 0px none solid none none -moz-use-text-color #333333 -moz-use-text-color -moz-use-text-color;" alt="[Image: ##SITE_NAME##]" src="##SITE_URL##/assets/img/logo.png" />
                  </a>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <div style=" padding: 20px; background-repeat: no-repeat; background-color: #ffffff; box-shadow: 0 0 7px rgba(0, 0, 0, 0.067);">
      <table style="background-color: #ffffff;" width="100%">
         <tbody>
            <tr>
               <td style="padding: 20px 30px 5px;">
                  <p style="color: #545454; font-size: 18px;">Hi ##USERNAME##,</p>
                  <table border="0" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div class="col-sm-9">
                                 <div class="clsShopPreviCart no-mar">
                                    <p class="dc clsPreHead textb">##SERVICE_PROVIDER_FIRSTNAME## ##SERVICE_PROVIDER_LASTNAME## made interest to work ##SERVICENAME## you posted.</p>
                                    <p>
                                    See his calendar and book if the profile is good for you ##CALENDAR_URL##
                                    </p>
                                    
                                    <div class="clsCardInnBg"> </div>
                                 </div>
                              </div>
                           </td>
			
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
						</tr>
                     </tbody>
                  </table>
                  <table style="background-color: #ffffff;" width="100%">
                     <tbody>
                        <tr>
                           <td>
                              <div style="border-top: 1px solid #d6d6d6; padding: 15px 30px 25px; margin: 0pt 30px;">
                                 <h4 style=" font-size: 22px; font-weight: bold; text-align: center; margin: 0pt 0pt 5px; line-height: 26px; color: #30BCEF;">Thanks,</h4>
                                 <h5 style=" color: #545454; line-height: 20px; text-align: center; margin: 0pt; font-size: 16px;">##SITE_NAME## - <a style="color: #30BCEF;" title="##SITE_NAME## - Collective Buying Power" href="##SITE_URL##" target="_blank">##SITE_URL##</a></h5>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table style="margin-top: 2px; background-color: #f5f5f5;" width="100%">
         <tbody>
            <tr>
               <td>
                  <p style=" color: #000; font-size: 12px; text-align: center; line-height: 16px; margin: 10px;">Need help? Have feedback? Feel free to <a style="color: #30BCEF;" title="Contact ##SITE_NAME##" href="##CONTACT_URL##" target="_blank">Contact Us</a></p>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
   <table cellspacing="0" cellpadding="0" width="720px">
      <tbody>
         <tr>
            <td align="center">
               <p style="font-size: 11px; color: #929292; margin: 3px; padding-top: 10px;">Delivered by <a style="color: #30BCEF;" title="##SITE_NAME##" href="##SITE_LINK##" target="_blank">##SITE_NAME##</a></p>
            </td>
         </tr>
      </tbody>
   </table>
</div>',
                'from_name' => '##FROM_EMAIL##',
                'info' => 'We will send this email when request user post their interest',
                'reply_to' => '##REPLY_TO_EMAIL##',
                'plugin' => 'Request/Request'
            ]                                   
        ];

        $posts = $this->table('email_templates');
        $posts->insert($data)
              ->save();
    }
}
