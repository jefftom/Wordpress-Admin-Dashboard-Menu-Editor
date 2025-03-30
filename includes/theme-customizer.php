<?php
/**
 * Admin Theme Customizer template
 *
 * @package Multiple_Menus_Admin_Editor
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
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
            </div>
            
            <div class="came-theme-preview">
                <h3><?php echo esc_html__('Live Preview', 'multiple-menus-admin-editor'); ?></h3>
                
                <div class="came-preview-container">
                    <div class="came-preview-menu">
                        <div class="came-preview-item active">
                            <div class="came-preview-icon dashicons dashicons-dashboard"></div>
                            <div class="came-preview-label">Dashboard</div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-post"></div>
                            <div class="came-preview-label">Posts</div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-media"></div>
                            <div class="came-preview-label">Media</div>
                        </div>
                        <div class="came-preview-item current">
                            <div class="came-preview-icon dashicons dashicons-admin-appearance"></div>
                            <div class="came-preview-label">Appearance</div>
                            
                            <div class="came-preview-submenu">
                                <div class="came-preview-subitem">Themes</div>
                                <div class="came-preview-subitem current">Customize</div>
                                <div class="came-preview-subitem">Widgets</div>
                                <div class="came-preview-subitem">Menus</div>
                            </div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-plugins"></div>
                            <div class="came-preview-label">Plugins</div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-users"></div>
                            <div class="came-preview-label">Users</div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-tools"></div>
                            <div class="came-preview-label">Tools</div>
                        </div>
                        <div class="came-preview-item">
                            <div class="came-preview-icon dashicons dashicons-admin-settings"></div>
                            <div class="came-preview-label">Settings</div>
                        </div>
                    </div>
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
        
        var css = '';
        
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
            
            // Add custom CSS
            if (customCSS) {
                css += '\n\n/* Custom CSS */\n' + customCSS;
            }
        }
        
        // Update preview styles
        $('#came-preview-styles').html(css);
    }
    
    // Save theme settings
    function saveThemeSettings() {
        // Show loading indicator
        var $saveButton = $('#came-save-theme');
        var originalText = $saveButton.html();
        $saveButton.html('<span class="dashicons dashicons-update spinning"></span> Saving...');
        
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
            custom_css: $('#came-custom-css').val()
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
                    showMessage(response.data.message || 'Error saving theme settings.', 'error');
                }
                $saveButton.html(originalText);
            },
            error: function() {
                showMessage('AJAX error. Please try again.', 'error');
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
        
        updatePreview();
        showMessage('Theme settings reset to default.', 'info');
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
</script>