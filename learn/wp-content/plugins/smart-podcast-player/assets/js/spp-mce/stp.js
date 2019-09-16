( function() {
    tinymce.PluginManager.add( 'stp', function( editor, url ) {

        // Add a button that opens a window
        editor.addButton( 'stp_button_key', {

            icon: 'stp-icon',
            onclick: function() {
				
				var colorPrompt;
				var downloadablePrompt;
				var socialPrompt;
				if( isPaidVersionAdmin() ) {
					// For the paid version, the color goes in a text box
					colorPrompt = {
						type: 'textbox',
						name: 'color',
						label: 'Color (Hex) #'
					};
					// The paid version has the downloadable option
					downloadablePrompt = {
						type: 'listbox',
						name: 'download',
						label: 'Downloadable?',
						onselect: function(e) {},
						values: [{text: 'Yes', value: 'on'}, {text: 'No', value: 'off'}]
					};

					socialPrompt = {
	                     type: 'listbox',
	                     name: 'social',
	                     label: 'Social Sharing',
	                     onselect: function(e) {},
	                     values: [{text: 'On', value: 'on'}, {text: 'Off', value: 'off'}]
	                };
					socialOptionsPrompt = {
						type: 'container',
						name: 'social_opts',
						label: 'Social Options',
						tooltip: 'Choose up to three social sharing sites.',
						html: '<table> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_twitter" checked>Twitter</input></td> \
									<td><input type="checkbox" id="spp_socialopt_facebook" checked>Facebook</input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_gplus" checked>Google+</input></td> \
									<td><input type="checkbox" id="spp_socialopt_linkedin">LinkedIn</input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_stumble">StumbleUpon</input></td> \
									<td><input type="checkbox" id="spp_socialopt_pinterest">Pinterest</input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_email">Email</input></td> \
									<td></td> \
								</tr> \
								</table>'
					};
				} else {
					// For the free version, only one color is available
					colorPrompt = {
						type: 'listbox',
						name: 'color',
						label: 'Color',
						onselect: function(e) {},
						values: [{text: 'Green', value: '60b86c'}],
						disabled: true,
						tooltip: 'Upgrade to choose any color of the rainbow!'
					}
					// The free version has no downloadable option
						downloadablePrompt = {
						type: 'listbox',
						name: 'download',
						label: 'Downloadable?',
						onselect: function(e) {},
						values: [{text: 'No', value: 'off'}],
						disabled: true,
						tooltip: 'If you upgrade, you can decide whether to add a nifty download button to your player.'
					};

					socialPrompt = {
	                     type: 'listbox',
	                     name: 'social',
	                     label: 'Social Sharing',
	                     onselect: function(e) {},
	                     values: [{text: 'Off', value: 'off'}],
						 disabled: true,
						 tooltip: 'Your listeners can tell their friends about your show with ease when you upgrade.'
	                };
					socialOptionsPrompt = {
						type: 'container',
						name: 'social_opts',
						label: 'Social Options',
						disabled: true,
						tooltip: 'Your listeners can tell their friends about your show with ease when you upgrade.',
						html: '<table> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_twitter" disabled><label class="mce-label mce-disabled">Twitter</label></input></td> \
									<td><input type="checkbox" id="spp_socialopt_facebook" disabled><label class="mce-label mce-disabled">Facebook</label></input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_gplus" disabled><label class="mce-label mce-disabled">Google+</label></input></td> \
									<td><input type="checkbox" id="spp_socialopt_linkedin" disabled><label class="mce-label mce-disabled">LinkedIn</label></input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_stumble" disabled><label class="mce-label mce-disabled">StumbleUpon</label></input></td> \
									<td><input type="checkbox" id="spp_socialopt_pinterest" disabled><label class="mce-label mce-disabled">Pinterest</label></input></td> \
								</tr> \
								<tr> \
									<td><input type="checkbox" id="spp_socialopt_email" disabled><label class="mce-label mce-disabled">Email</label></input></td> \
									<td></td> \
								</tr> \
								</table>'
					};
				}
				
                // Open window
                editor.windowManager.open( {

                    title: 'Smart Track Player Shortcode',
                    body: [
	                    {
	                        type: 'textbox',
	                        name: 'url',
	                        label: 'URL'
	                    },
	                    {
	                        type: 'textbox',
	                        name: 'title',
	                        label: 'Title'
	                    },
	                    {
	                        type: 'textbox',
	                        name: 'artist',
	                        label: 'Artist'
	                    },
	                    {
	                        type: 'textbox',
	                        name: 'image',
	                        label: 'Image URL'
	                    },
	                    colorPrompt,
	                    socialPrompt,
						socialOptionsPrompt,
	                    downloadablePrompt
 
	                ],
                    onsubmit: function( e ) {

                    	var shortcode = '[smart_track_player';

                    	if( e.data.url != '' )
                    		shortcode += ' url="' + e.data.url + '" ';

                    	if( e.data.title != '' )
                    		shortcode += ' title="' + e.data.title + '" ';

                    	if( e.data.artist != '' )
                    		shortcode += ' artist="' + e.data.artist + '" ';

                    	if( e.data.image != '' )
                    		shortcode += ' image="' + e.data.image + '" ';

                    	if( isPaidVersionAdmin() ) {

                    		if( e.data.color != '' )
                    			shortcode += ' color="' + e.data.color + '" ';

                    		if( e.data.download != 'on' )
                    			shortcode += ' download="false" ';

                    		if( e.data.social != 'on' ) {
                    			shortcode += ' social="false" ';
							} else {
								shortcode += ' social="true" ';
								if( jQuery("#spp_socialopt_twitter").is( ":checked" ) )
									shortcode += ' social_twitter="true" ';
								if( jQuery("#spp_socialopt_facebook").is( ":checked" ) )
									shortcode += ' social_facebook="true" ';
								if( jQuery("#spp_socialopt_gplus").is( ":checked" ) )
									shortcode += ' social_gplus="true" ';
								if( jQuery("#spp_socialopt_linkedin").is( ":checked" ) )
									shortcode += ' social_linkedin="true" ';
								if( jQuery("#spp_socialopt_stumble").is( ":checked" ) )
									shortcode += ' social_stumble="true" ';
								if( jQuery("#spp_socialopt_pinterest").is( ":checked" ) )
									shortcode += ' social_pinterest="true" ';
								if( jQuery("#spp_socialopt_email").is( ":checked" ) )
									shortcode += ' social_email="true" ';
							}
                    	}

                    	shortcode += ']';

                        // Insert content when the window form is submitted
                        if( e.data.url != '' )
                            editor.insertContent( shortcode );
                        else
                            editor.windowManager.alert("URL is required.");

                    }

                } );
            }

        } );

    } );

} )();
