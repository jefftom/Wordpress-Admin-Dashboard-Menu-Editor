</div>
                
                <div class="came-preview-info">
                    <p><?php echo esc_html__('This is a live preview of how your admin menu will look with the selected theme settings.', 'multiple-menus-admin-editor'); ?></p>
                    <p><?php echo esc_html__('Hover over items to see the hover effect.', 'multiple-menus-admin-editor'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style id="came-preview-styles"></style>

<script>
jQuery(document).ready(function($) {
    // Initialize range sliders
    $('#came-border-radius, #came-transition-speed, #came-menu-spacing').on('input', function() {
        $('#' + this.id + '-value').val(this.value);
        updatePreview();
    });
    
    // Theme preset change
    $('#came-theme-preset').on('change', function() {
        var preset = $(this).val();
        
        // Show/hide custom colors section
        if (preset === 'custom') {
            $('#came-custom-colors').show();
        } else {
            $('#came-custom-colors').hide();
            
            // Set preset colors
            var presetColors = getPresetColors(preset);
            
            // Update color inputs
            $('#came-menu-bg-color').val(presetColors.menuBg);
            $('#came-menu-text-color').val(presetColors.menuText);
            $('#came-menu-hover-bg-color').val(presetColors.menuHoverBg);
            $('#came-menu-hover-text-color').val(presetColors.menuHoverText);
            $('#came-submenu-bg-color').val(presetColors.submenuBg);
            $('#came-submenu-text-color').val(presetColors.submenuText);
            $('#came-submenu-hover-bg-color').val(presetColors.submenuHoverBg);
            $('#came-submenu-hover-text-color').val(presetColors.submenuHoverText);
        }
        
        updatePreview();
    });
    
    // Color changes
    $('.came-color-option input').on('input', function() {
        updatePreview();
    });
    
    // Toggle theme enable/disable
    $('#came-theme-enabled').on('change', function() {
        updatePreview();
    });
    
    // Toggle custom fonts
    $('#came-enable-custom-fonts').on('change', function() {
        if ($(this).is(':checked')) {
            $('#came-font-selector').slideDown();
        } else {
            $('#came-font-selector').slideUp();
        }
        updatePreview();
    });
    
    // Font selectors
    $('#came-primary-font, #came-secondary-font, #came-font-weight').on('change', function() {
        updatePreview();
        updateFontPreview();
    });
    
    // Font size slider
    $('#came-font-size').on('input', function() {
        $('#came-font-size-value').val(this.value);
        updatePreview();
        updateFontPreview();
    });
    
    // Custom CSS changes
    $('#came-custom-css').on('input', function() {
        updatePreview();
    });
    
    // Save theme settings
    $('#came-save-theme').on('click', function() {
        saveThemeSettings();
    });
    
    // Reset theme
    $('#came-reset-theme').on('click', function() {
        if (confirm('<?php echo esc_js(__('Are you sure you want to reset theme settings to default?', 'multiple-menus-admin-editor')); ?>')) {
            resetThemeSettings();
        }
    });
    
    // Initial preview update
    updatePreview();
    updateFontPreview();
    
    // Function to update the font preview
    function updateFontPreview() {
        var enableCustomFonts = $('#came-enable-custom-fonts').is(':checked');
        var primaryFont = $('#came-primary-font').val();
        var secondaryFont = $('#came-secondary-font').val();
        var fontSize = $('#came-font-size').val();
        var fontWeight = $('#came-font-weight').val();
        
        // Reset classes first
        $('#came-primary-font-preview, #came-secondary-font-preview').attr('class', '');
        $('#came-primary-font-preview').addClass('came-primary-font-preview');
        $('#came-secondary-font-preview').addClass('came-secondary-font-preview');
        
        if (enableCustomFonts) {
            if (primaryFont !== 'default') {
                var primaryFontClass = 'font-' + primaryFont.toLowerCase().replace(/ /g, '-');
                $('#came-primary-font-preview').addClass(primaryFontClass);
                
                // Load Google Font for preview
                var fontFamilies = [primaryFont];
                if (secondaryFont !== 'default' && secondaryFont !== primaryFont) {
                    fontFamilies.push(secondaryFont);
                }
                updateGoogleFonts(fontFamilies);
            }
            
            if (secondaryFont !== 'default') {
                var secondaryFontClass = 'font-' + secondaryFont.toLowerCase().replace(/ /g, '-');
                $('#came-secondary-font-preview').addClass(secondaryFontClass);
            }
            
            // Apply font weight
            if (fontWeight !== 'normal') {
                var weightClass = 'font-weight-' + fontWeight;
                $('#came-primary-font-preview, #came-secondary-font-preview').addClass(weightClass);
            }
            
            // Apply font size
            $('#came-primary-font-preview').css('font-size', parseInt(fontSize) + 4 + 'px');
            $('#came-secondary-font-preview').css('font-size', fontSize + 'px');
        } else {
            // Reset to default styles
            $('#came-primary-font-preview, #came-secondary-font-preview').css('font-weight', '');
            $('#came-primary-font-preview').css('font-size', '');
            $('#came-secondary-font-preview').css('font-size', '');
        }
    }
    
    // Function to get preset colors
    function getPresetColors(preset) {
        var presets = {
            westworld: {
                menuBg: '#ffffff',
                menuText: '#000000',
                menuHoverBg: '#000000',
                menuHoverText: '#ffffff',
                submenuBg: '#f8f8f8',
                submenuText: '#000000',
                submenuHoverBg: '#000000',
                submenuHoverText: '#ffffff'
            },
            ocean: {
                menuBg: '#f9f9f9',
                menuText: '#2271b1',
                menuHoverBg: '#2271b1',
                menuHoverText: '#ffffff',
                submenuBg: '#ffffff',
                submenuText: '#2271b1',
                submenuHoverBg: '#2271b1',
                submenuHoverText: '#ffffff'
            },
            forest: {
                menuBg: '#f9f9f9',
                menuText: '#2e7d32',
                menuHoverBg: '#2e7d32',
                menuHoverText: '#ffffff',
                submenuBg: '#ffffff',
                submenuText: '#2e7d32',
                submenuHoverBg: '#2e7d32',
                submenuHoverText: '#ffffff'
            },
            sunset: {
                menuBg: '#f9f9f9',
                menuText: '#d84315',
                menuHoverBg: '#d84315',
                menuHoverText: '#ffffff',
                submenuBg: '#ffffff',
                submenuText: '#d84315',
                submenuHoverBg: '#d84315',
                submenuHoverText: '#ffffff'
            }
        };
        
        return presets[preset] || presets.westworld;
    }
    
    // Update preview
    function updatePreview() {
        var enabled = $('#came-theme-enabled').is(':checked');
        var menuBg = $('#came-menu-bg-color').val();
        var menuText = $('#came-menu-text-color').val();
        var menuHoverBg = $('#came-menu-hover-bg-color').val();
        var menuHoverText = $('#came-menu-hover-text-color').val();
        var submenuBg = $('#came-submenu-bg-color').val();
        var submenuText = $('#came-submenu-text-color').val();
        var submenuHoverBg = $('#came-submenu-hover-bg-color').val();
        var submenuHoverText = $('#came-submenu-hover-text-color').val();
        var borderRadius = $('#came-border-radius').val();
        var transitionSpeed = $('#came-transition-speed').val();
        var spacing = $('#came-menu-spacing').val();
        var customCSS = $('#came-custom-css').val();
        
        // Typography options
        var enableCustomFonts = $('#came-enable-custom-fonts').is(':checked');
        var primaryFont = $('#came-primary-font').val();
        var secondaryFont = $('#came-secondary-font').val();
        var fontSize = $('#came-font-size').val();
        var fontWeight = $('#came-font-weight').val();
        
        var css = '';
        var fontFamilies = [];
        
        // Add Google Fonts if enabled
        if (enabled && enableCustomFonts) {
            if (primaryFont !== 'default') {
                fontFamilies.push(primaryFont);
            }
            if (secondaryFont !== 'default' && secondaryFont !== primaryFont) {
                fontFamilies.push(secondaryFont);
            }
            
            // Update font preview in real-time
            updateGoogleFonts(fontFamilies);
        }
        
        if (enabled) {
            // Preview CSS
            css += `.came-preview-menu {
                background-color: ${menuBg};
            }
            
            .came-preview-item {
                margin-bottom: ${spacing/2}px;
                border-radius: ${borderRadius}px;
                transition: all ${transitionSpeed}s ease;
            }
            
            .came-preview-item:not(.current) {
                color: ${menuText};
            }
            
            .came-preview-item:hover:not(.current),
            .came-preview-item:focus:not(.current) {
                background-color: ${menuHoverBg};
                color: ${menuHoverText};
            }
            
            .came-preview-item.current {
                background-color: ${menuHoverBg};
                color: ${menuHoverText};
            }
            
            .came-preview-submenu {
                background-color: ${submenuBg};
            }
            
            .came-preview-subitem {
                color: ${submenuText};
                transition: all ${transitionSpeed}s ease;
                border-radius: ${borderRadius}px;
            }
            
            .came-preview-subitem:hover:not(.current),
            .came-preview-subitem:focus:not(.current) {
                background-color: ${submenuHoverBg};
                color: ${submenuHoverText};
            }
            
            .came-preview-subitem.current {
                background-color: ${submenuHoverBg};
                color: ${submenuHoverText};
            }`;
            
            // Add typography styles if enabled
            if (enableCustomFonts) {
                css += `
                .came-preview-menu {
                    font-size: ${fontSize}px;
                }`;
                
                if (primaryFont !== 'default') {
                    css += `
                    .came-preview-item {
                        font-family: "${primaryFont}", sans-serif;
                        font-weight: ${fontWeight};
                    }`;
                }
                
                if (secondaryFont !== 'default') {
                    css += `
                    .came-preview-submenu .came-preview-subitem {
                        font-family: "${secondaryFont}", sans-serif;
                    }`;
                }
            }
            
            // Add custom CSS
            if (customCSS) {
                css += '\n\n/* Custom CSS */\n' + customCSS;
            }
        }
        
        // Update preview styles
        $('#came-preview-styles').html(css);
    }
    
    // Function to update Google Fonts
    function updateGoogleFonts(fontFamilies) {
        if (!fontFamilies || fontFamilies.length === 0) {
            return;
        }
        
        // Remove any existing Google Fonts link
        $('link[data-came-fonts]').remove();
        
        // Create the Google Fonts URL
        var fontsUrl = 'https://fonts.googleapis.com/css2?';
        var fontQueries = [];
        
        // Add each font to the URL
        fontFamilies.forEach(function(font) {
            // Replace spaces with plus signs
            var encodedFont = font.replace(/ /g, '+');
            fontQueries.push('family=' + encodedFont + ':wght@300;400;500;600;700');
        });
        
        fontsUrl += fontQueries.join('&');
        fontsUrl += '&display=swap';
        
        // Add the link element to the head
        $('<link>')
            .attr({
                'rel': 'stylesheet',
                'href': fontsUrl,
                'data-came-fonts': 'true'
            })
            .appendTo('head');
    }
    
    // Save theme settings
    function saveThemeSettings() {
        // Show loading indicator
        var $saveButton = $('#came-save-theme');
        var originalText = $saveButton.html();
        $saveButton.html('<span class="dashicons dashicons-update spinning"></span> <?php echo esc_js(__('Saving...', 'multiple-menus-admin-editor')); ?>');
        
        var themeSettings = {
            enabled: $('#came-theme-enabled').is(':checked'),
            preset: $('#came-theme-preset').val(),
            menu_bg_color: $('#came-menu-bg-color').val(),
            menu_text_color: $('#came-menu-text-color').val(),
            menu_hover_bg_color: $('#came-menu-hover-bg-color').val(),
            menu_hover_text_color: $('#came-menu-hover-text-color').val(),
            submenu_bg_color: $('#came-submenu-bg-color').val(),
            submenu_text_color: $('#came-submenu-text-color').val(),
            submenu_hover_bg_color: $('#came-submenu-hover-bg-color').val(),
            submenu_hover_text_color: $('#came-submenu-hover-text-color').val(),
            border_radius: $('#came-border-radius').val(),
            transition_speed: $('#came-transition-speed').val(),
            spacing: $('#came-menu-spacing').val(),
            custom_css: $('#came-custom-css').val(),
            // Typography options
            enable_custom_fonts: $('#came-enable-custom-fonts').is(':checked'),
            primary_font: $('#came-primary-font').val(),
            secondary_font: $('#came-secondary-font').val(),
            font_size: $('#came-font-size').val(),
            font_weight: $('#came-font-weight').val()
        };
        
        // AJAX save
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'came_save_theme',
                nonce: '<?php echo wp_create_nonce('came_theme_nonce'); ?>',
                theme_settings: themeSettings
            },
            success: function(response) {
                if (response.success) {
                    showMessage(response.data.message, 'success');
                } else {
                    showMessage(response.data.message || '<?php echo esc_js(__('Error saving theme settings.', 'multiple-menus-admin-editor')); ?>', 'error');
                }
                $saveButton.html(originalText);
            },
            error: function() {
                showMessage('<?php echo esc_js(__('AJAX error. Please try again.', 'multiple-menus-admin-editor')); ?>', 'error');
                $saveButton.html(originalText);
            }
        });
    }
    
    // Reset theme settings
    function resetThemeSettings() {
        // Reset to default values
        $('#came-theme-enabled').prop('checked', false);
        $('#came-theme-preset').val('westworld');
        $('#came-custom-colors').hide();
        
        var defaultColors = getPresetColors('westworld');
        $('#came-menu-bg-color').val(defaultColors.menuBg);
        $('#came-menu-text-color').val(defaultColors.menuText);
        $('#came-menu-hover-bg-color').val(defaultColors.menuHoverBg);
        $('#came-menu-hover-text-color').val(defaultColors.menuHoverText);
        $('#came-submenu-bg-color').val(defaultColors.submenuBg);
        $('#came-submenu-text-color').val(defaultColors.submenuText);
        $('#came-submenu-hover-bg-color').val(defaultColors.submenuHoverBg);
        $('#came-submenu-hover-text-color').val(defaultColors.submenuHoverText);
        
        $('#came-border-radius').val(4);
        $('#came-border-radius-value').val(4);
        $('#came-transition-speed').val(0.3);
        $('#came-transition-speed-value').val(0.3);
        $('#came-menu-spacing').val(15);
        $('#came-menu-spacing-value').val(15);
        $('#came-custom-css').val('');
        
        // Reset typography options
        $('#came-enable-custom-fonts').prop('checked', false);
        $('#came-font-selector').hide();
        $('#came-primary-font').val('default');
        $('#came-secondary-font').val('default');
        $('#came-font-size').val(14);
        $('#came-font-size-value').val(14);
        $('#came-font-weight').val('normal');
        
        // Remove any Google Fonts link
        $('link[data-came-fonts]').remove();
        
        updatePreview();
        showMessage('<?php echo esc_js(__('Theme settings reset to default.', 'multiple-menus-admin-editor')); ?>', 'info');
    }
    
    // Show message
    function showMessage(message, type) {
        var $messageBox = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>')
            .hide()
            .insertAfter('.came-theme-container')
            .slideDown();
            
        setTimeout(function() {
            $messageBox.slideUp(function() {
                $(this).remove();
            });
        }, 4000);
    }
});
</script><?php
/**
 * Admin Theme Customizer template
 *
 * @package Multiple_Menus_Admin_Editor
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Verify user capabilities
if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'multiple-menus-admin-editor'));
}

// Get saved theme settings
$theme_settings = get_option('came_theme_settings', array(
    'enabled' => false,
    'preset' => 'westworld',
    'menu_bg_color' => '#ffffff',
    'menu_text_color' => '#000000',
    'menu_hover_bg_color' => '#000000',
    'menu_hover_text_color' => '#ffffff',
    'submenu_bg_color' => '#f8f8f8',
    'submenu_text_color' => '#000000',
    'submenu_hover_bg_color' => '#000000',
    'submenu_hover_text_color' => '#ffffff',
    'border_radius' => '4',
    'transition_speed' => '0.3',
    'spacing' => '15',
    'custom_css' => '',
));
?>

<div class="wrap came-admin-wrap">
    <h1><?php echo esc_html__('Admin Theme Customizer', 'multiple-menus-admin-editor'); ?></h1>
    
    <div class="came-admin-container came-theme-customizer">
        <div class="came-admin-header">
            <div class="came-theme-selector">
                <h3><?php echo esc_html__('Theme Settings', 'multiple-menus-admin-editor'); ?></h3>
            </div>
            
            <div class="came-actions">
                <button id="came-save-theme" class="button button-primary">
                    <span class="dashicons dashicons-saved"></span>
                    <?php echo esc_html__('Save Theme Settings', 'multiple-menus-admin-editor'); ?>
                </button>
                <button id="came-reset-theme" class="button">
                    <span class="dashicons dashicons-undo"></span>
                    <?php echo esc_html__('Reset to Default', 'multiple-menus-admin-editor'); ?>
                </button>
            </div>
        </div>
        
        <div class="came-theme-container">
            <div class="came-theme-options">
                <div class="came-option-group">
                    <label class="came-toggle-switch">
                        <input type="checkbox" id="came-theme-enabled" <?php checked($theme_settings['enabled'], true); ?>>
                        <span class="came-toggle-slider"></span>
                        <?php echo esc_html__('Enable Custom Theme', 'multiple-menus-admin-editor'); ?>
                    </label>
                    <p class="description"><?php echo esc_html__('Apply custom styling to the WordPress admin menu.', 'multiple-menus-admin-editor'); ?></p>
                </div>
                
                <div class="came-option-group">
                    <label for="came-theme-preset"><?php echo esc_html__('Theme Preset', 'multiple-menus-admin-editor'); ?></label>
                    <select id="came-theme-preset">
                        <option value="westworld" <?php selected($theme_settings['preset'], 'westworld'); ?>><?php echo esc_html__('Westworld (Black & White)', 'multiple-menus-admin-editor'); ?></option>
                        <option value="ocean" <?php selected($theme_settings['preset'], 'ocean'); ?>><?php echo esc_html__('Ocean Blue', 'multiple-menus-admin-editor'); ?></option>
                        <option value="forest" <?php selected($theme_settings['preset'], 'forest'); ?>><?php echo esc_html__('Forest Green', 'multiple-menus-admin-editor'); ?></option>
                        <option value="sunset" <?php selected($theme_settings['preset'], 'sunset'); ?>><?php echo esc_html__('Sunset Orange', 'multiple-menus-admin-editor'); ?></option>
                        <option value="custom" <?php selected($theme_settings['preset'], 'custom'); ?>><?php echo esc_html__('Custom Colors', 'multiple-menus-admin-editor'); ?></option>
                    </select>
                </div>
                
                <div id="came-custom-colors" <?php echo $theme_settings['preset'] === 'custom' ? '' : 'style="display:none;"'; ?>>
                    <h3><?php echo esc_html__('Main Menu Colors', 'multiple-menus-admin-editor'); ?></h3>
                    
                    <div class="came-color-grid">
                        <div class="came-color-option">
                            <label for="came-menu-bg-color"><?php echo esc_html__('Background Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-menu-bg-color" value="<?php echo esc_attr($theme_settings['menu_bg_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-menu-text-color"><?php echo esc_html__('Text Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-menu-text-color" value="<?php echo esc_attr($theme_settings['menu_text_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-menu-hover-bg-color"><?php echo esc_html__('Hover Background', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-menu-hover-bg-color" value="<?php echo esc_attr($theme_settings['menu_hover_bg_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-menu-hover-text-color"><?php echo esc_html__('Hover Text Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-menu-hover-text-color" value="<?php echo esc_attr($theme_settings['menu_hover_text_color']); ?>">
                        </div>
                    </div>
                    
                    <h3><?php echo esc_html__('Submenu Colors', 'multiple-menus-admin-editor'); ?></h3>
                    
                    <div class="came-color-grid">
                        <div class="came-color-option">
                            <label for="came-submenu-bg-color"><?php echo esc_html__('Background Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-submenu-bg-color" value="<?php echo esc_attr($theme_settings['submenu_bg_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-submenu-text-color"><?php echo esc_html__('Text Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-submenu-text-color" value="<?php echo esc_attr($theme_settings['submenu_text_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-submenu-hover-bg-color"><?php echo esc_html__('Hover Background', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-submenu-hover-bg-color" value="<?php echo esc_attr($theme_settings['submenu_hover_bg_color']); ?>">
                        </div>
                        
                        <div class="came-color-option">
                            <label for="came-submenu-hover-text-color"><?php echo esc_html__('Hover Text Color', 'multiple-menus-admin-editor'); ?></label>
                            <input type="color" id="came-submenu-hover-text-color" value="<?php echo esc_attr($theme_settings['submenu_hover_text_color']); ?>">
                        </div>
                    </div>
                </div>
                
                <h3><?php echo esc_html__('Style Options', 'multiple-menus-admin-editor'); ?></h3>
                
                <div class="came-option-grid">
                    <div class="came-option-group">
                        <label for="came-border-radius"><?php echo esc_html__('Border Radius (px)', 'multiple-menus-admin-editor'); ?></label>
                        <input type="range" id="came-border-radius" min="0" max="20" value="<?php echo esc_attr($theme_settings['border_radius']); ?>">
                        <output for="came-border-radius" id="came-border-radius-value"><?php echo esc_html($theme_settings['border_radius']); ?></output>
                    </div>
                    
                    <div class="came-option-group">
                        <label for="came-transition-speed"><?php echo esc_html__('Transition Speed (seconds)', 'multiple-menus-admin-editor'); ?></label>
                        <input type="range" id="came-transition-speed" min="0" max="1" step="0.1" value="<?php echo esc_attr($theme_settings['transition_speed']); ?>">
                        <output for="came-transition-speed" id="came-transition-speed-value"><?php echo esc_html($theme_settings['transition_speed']); ?></output>
                    </div>
                    
                    <div class="came-option-group">
                        <label for="came-menu-spacing"><?php echo esc_html__('Menu Item Spacing (px)', 'multiple-menus-admin-editor'); ?></label>
                        <input type="range" id="came-menu-spacing" min="5" max="30" value="<?php echo esc_attr($theme_settings['spacing']); ?>">
                        <output for="came-menu-spacing" id="came-menu-spacing-value"><?php echo esc_html($theme_settings['spacing']); ?></output>
                    </div>
                </div>
                
                <div class="came-option-group">
                    <label for="came-custom-css"><?php echo esc_html__('Additional Custom CSS', 'multiple-menus-admin-editor'); ?></label>
                    <textarea id="came-custom-css" rows="6" placeholder="<?php echo esc_attr__('Add your custom CSS here...', 'multiple-menus-admin-editor'); ?>"><?php echo esc_textarea($theme_settings['custom_css']); ?></textarea>
                    <p class="description"><?php echo esc_html__('Add additional CSS rules to customize the admin menu further.', 'multiple-menus-admin-editor'); ?></p>
                </div>
                
                <!-- Google Fonts Integration -->
                <div class="came-option-group">
                    <h3><?php echo esc_html__('Typography Options', 'multiple-menus-admin-editor'); ?></h3>
                    <p class="description"><?php echo esc_html__('Customize the fonts used in your admin dashboard.', 'multiple-menus-admin-editor'); ?></p>
                    
                    <div class="came-typography-section">
                        <div class="came-form-group">
                            <label for="came-enable-custom-fonts">
                                <input type="checkbox" id="came-enable-custom-fonts" <?php checked(isset($theme_settings['enable_custom_fonts']) ? $theme_settings['enable_custom_fonts'] : false); ?>>
                                <?php echo esc_html__('Enable custom fonts', 'multiple-menus-admin-editor'); ?>
                            </label>
                        </div>
                        
                        <div class="came-font-selector" id="came-font-selector" <?php echo (isset($theme_settings['enable_custom_fonts']) && $theme_settings['enable_custom_fonts']) ? '' : 'style="display:none;"'; ?>>
                            <div class="came-form-group">
                                <label for="came-primary-font"><?php echo esc_html__('Primary Font', 'multiple-menus-admin-editor'); ?></label>
                                <select id="came-primary-font">
                                    <option value="default" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : 'default', 'default'); ?>><?php echo esc_html__('Default', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="Roboto" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Roboto'); ?>>Roboto</option>
                                    <option value="Open Sans" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Open Sans'); ?>>Open Sans</option>
                                    <option value="Lato" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Lato'); ?>>Lato</option>
                                    <option value="Montserrat" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Montserrat'); ?>>Montserrat</option>
                                    <option value="Source Sans Pro" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Source Sans Pro'); ?>>Source Sans Pro</option>
                                    <option value="Raleway" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Raleway'); ?>>Raleway</option>
                                    <option value="Nunito" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Nunito'); ?>>Nunito</option>
                                    <option value="Ubuntu" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Ubuntu'); ?>>Ubuntu</option>
                                    <option value="Poppins" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Poppins'); ?>>Poppins</option>
                                    <option value="Playfair Display" <?php selected(isset($theme_settings['primary_font']) ? $theme_settings['primary_font'] : '', 'Playfair Display'); ?>>Playfair Display</option>
                                </select>
                                <p class="description"><?php echo esc_html__('Select the main font for menu items and headings.', 'multiple-menus-admin-editor'); ?></p>
                            </div>
                            
                            <div class="came-form-group">
                                <label for="came-secondary-font"><?php echo esc_html__('Secondary Font', 'multiple-menus-admin-editor'); ?></label>
                                <select id="came-secondary-font">
                                    <option value="default" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : 'default', 'default'); ?>><?php echo esc_html__('Default', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="Roboto" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Roboto'); ?>>Roboto</option>
                                    <option value="Open Sans" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Open Sans'); ?>>Open Sans</option>
                                    <option value="Lato" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Lato'); ?>>Lato</option>
                                    <option value="Montserrat" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Montserrat'); ?>>Montserrat</option>
                                    <option value="Source Sans Pro" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Source Sans Pro'); ?>>Source Sans Pro</option>
                                    <option value="Raleway" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Raleway'); ?>>Raleway</option>
                                    <option value="Nunito" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Nunito'); ?>>Nunito</option>
                                    <option value="Ubuntu" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Ubuntu'); ?>>Ubuntu</option>
                                    <option value="Poppins" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Poppins'); ?>>Poppins</option>
                                    <option value="Merriweather" <?php selected(isset($theme_settings['secondary_font']) ? $theme_settings['secondary_font'] : '', 'Merriweather'); ?>>Merriweather</option>
                                </select>
                                <p class="description"><?php echo esc_html__('Select the secondary font for body text and descriptions.', 'multiple-menus-admin-editor'); ?></p>
                            </div>
                            
                            <div class="came-form-group">
                                <label for="came-font-size"><?php echo esc_html__('Base Font Size', 'multiple-menus-admin-editor'); ?></label>
                                <div class="came-range-with-value">
                                    <input type="range" id="came-font-size" min="12" max="18" step="1" value="<?php echo esc_attr(isset($theme_settings['font_size']) ? $theme_settings['font_size'] : 14); ?>">
                                    <output for="came-font-size" id="came-font-size-value"><?php echo esc_html(isset($theme_settings['font_size']) ? $theme_settings['font_size'] : 14); ?></output>px
                                </div>
                                <p class="description"><?php echo esc_html__('Adjust the base font size for the admin menu.', 'multiple-menus-admin-editor'); ?></p>
                            </div>
                            
                            <div class="came-form-group">
                                <label for="came-font-weight"><?php echo esc_html__('Font Weight', 'multiple-menus-admin-editor'); ?></label>
                                <select id="came-font-weight">
                                    <option value="normal" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : 'normal', 'normal'); ?>><?php echo esc_html__('Normal', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="300" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : '', '300'); ?>><?php echo esc_html__('Light (300)', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="400" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : '', '400'); ?>><?php echo esc_html__('Regular (400)', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="500" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : '', '500'); ?>><?php echo esc_html__('Medium (500)', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="600" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : '', '600'); ?>><?php echo esc_html__('Semi-Bold (600)', 'multiple-menus-admin-editor'); ?></option>
                                    <option value="700" <?php selected(isset($theme_settings['font_weight']) ? $theme_settings['font_weight'] : '', '700'); ?>><?php echo esc_html__('Bold (700)', 'multiple-menus-admin-editor'); ?></option>
                                </select>
                                <p class="description"><?php echo esc_html__('Set the font weight for the menu items.', 'multiple-menus-admin-editor'); ?></p>
                            </div>
                            
                            <!-- Font Preview Section -->
                            <div class="came-font-preview">
                                <h4><?php echo esc_html__('Font Preview', 'multiple-menus-admin-editor'); ?></h4>
                                <div id="came-primary-font-preview" class="came-primary-font-preview">
                                    <?php echo esc_html__('This is how your primary font will look', 'multiple-menus-admin-editor'); ?>
                                </div>
                                <div id="came-secondary-font-preview" class="came-secondary-font-preview">
                                    <?php echo esc_html__('This is how your secondary font will look', 'multiple-menus-admin-editor'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="came-theme-preview">
                <h3><?php echo esc_html__('Live Preview', 'multiple-menus-admin-editor'); ?></h3>
                
                <div class="came-preview-container">
                    <div class="came-preview-menu">
                        <div class="came-preview-item active">
                            <div class="came-preview-icon dashicons dashicons-dashboard"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Dashboard', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-post"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Posts', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-media"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Media', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item current">
                            <div class="came-preview-icon dashicons dashicons-admin-appearance"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Appearance', 'multiple-menus-admin-editor'); ?></div>
                            
                            <div class="came-preview-submenu">
                                <div class="came-preview-subitem"><?php echo esc_html__('Themes', 'multiple-menus-admin-editor'); ?></div>
                                <div class="came-preview-subitem current"><?php echo esc_html__('Customize', 'multiple-menus-admin-editor'); ?></div>
                                <div class="came-preview-subitem"><?php echo esc_html__('Widgets', 'multiple-menus-admin-editor'); ?></div>
                                <div class="came-preview-subitem"><?php echo esc_html__('Menus', 'multiple-menus-admin-editor'); ?></div>
                            </div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-plugins"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Plugins', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-users"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Users', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-tools"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Tools', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-settings"></div>
                            <div class="came-preview-label"><?php echo esc_html__('Settings', 'multiple-menus-admin-editor'); ?></div>
                        </div>
                    </div>