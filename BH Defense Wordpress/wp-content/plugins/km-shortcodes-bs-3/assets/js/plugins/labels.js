// JavaScript Document
(function() {
    // Creates a new plugin class and a custom listbox
    tinymce.create('tinymce.plugins.km_labels', {
        createControl: function(n, cm) {
            switch (n) {                
                case 'km_labels':
                var c = cm.createSplitButton('km_labels', {
                    title : 'Boxes & frames shortcodes',
                    image : '../wp-content/plugins/km-shortcodes/assets/images/labels.png',
                    onclick : function() {
                    }
                });

                c.onRenderMenu.add(function(c, m) {
					// Boxes & frames
					m.add({title : 'Labels & Badges', 'class' : 'mceMenuItemTitle'}).setDisabled(1);
                    m.add({title : 'Default', onclick : function() {
                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[label]Default[/label]' );
                    }}); 
                    m.add({title : 'Success', onclick : function() {
                        tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '[label class="success"]Default[/label]' );
                    }});
                });

                // Return the new splitbutton instance
                return c;
                
            }
            return null;
        }
    });
    tinymce.PluginManager.add('km_labels', tinymce.plugins.km_labels);
})();
