# Video Wall Slider

A modern, production-ready WordPress plugin that displays a vertically scrollable "Video Wall" slider using YouTube videos, inspired by TikTok/Instagram Reels-style showcase grids.

## Features

### Frontend
- **Responsive Grid Layout**: 4-column responsive masonry/grid layout (adjustable)
- **YouTube Video Integration**: Embed unlimited YouTube videos
- **Auto-play & Loop**: Videos autoplay silently and loop continuously
- **Vertical Scrolling**: Smooth inertia-based scrolling with momentum
- **Viewport-Based Loading**: Videos play only when visible in viewport
- **Touch/Swipe Support**: Full touch support with smooth animations
- **Responsive Breakpoints**: Adapts columns based on screen size
- **Lazy Loading**: Optional lazy loading for better performance
- **Hover Effects**: Scale and overlay hover effects

### Admin Dashboard
- **Custom Admin Menu**: Dedicated "Video Wall Slider" menu
- **Multiple Instances**: Create unlimited video wall instances
- **Video Management**: 
  - Add unlimited YouTube video URLs
  - Drag-and-drop reordering
  - Remove/edit video entries
- **Layout Configuration**:
  - Number of visible columns
  - Autoplay on/off
  - Mute on/off
  - Loop on/off
  - Spacing between slides (px)
  - Border radius (px)
  - Lazy loading toggle
  - Hover effects (none, scale, overlay)
- **Auto-Generated Shortcode**: Each wall gets a unique shortcode
- **REST API**: Full REST API support for programmatic access

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. Go to "Video Wall Slider" in the admin menu to create your first wall

## Usage

### Creating a Video Wall

1. Navigate to **Video Wall Slider** → **Video Walls**
2. Click "Add New"
3. Enter a title for your wall
4. Add YouTube video URLs in the "Video URLs" meta box
5. Configure settings in the "Slider Settings" meta box
6. Click "Publish"

### Using the Shortcode

Copy the shortcode from the "Shortcode" meta box and paste it into any page or post:

```
[video_wall id="123"]
```

## Configuration Options

### Video Settings
- **Columns**: Number of visible columns (1-6, default: 4)
- **Autoplay**: Videos start playing automatically
- **Mute**: Videos play without sound
- **Loop**: Videos loop continuously
- **Spacing**: Gap between slides (0-50px, default: 10px)
- **Border Radius**: Rounded corners (0-50px, default: 8px)
- **Lazy Load**: Load videos only when visible
- **Hover Effect**: Scale, overlay, or none

## Technical Details

### Architecture
- **OOP Structure**: Clean, object-oriented code organization
- **Namespaced**: All classes use the `VideoWallSlider` namespace
- **Security**: Built-in nonce verification and sanitization
- **Performance**: Optimized with lazy loading and efficient rendering
- **Accessibility**: WCAG 2.1 compliant with reduced-motion support
- **Responsive**: Mobile-first design with CSS Grid

### File Structure
```
video-wall-slider/
├── video-wall-slider.php      # Main plugin file
├── includes/
│   ├── Core/
│   │   ├── Plugin.php         # Main plugin class
│   │   └── Installer.php      # Activation/deactivation
│   ├── Admin/
│   │   ├── Menu.php          # Admin menu
│   │   ├── MetaBoxes.php     # Meta box registration
│   │   └── Enqueuer.php      # Admin assets
│   ├── Frontend/
│   │   ├── Shortcode.php     # Shortcode handler
│   │   └── Enqueuer.php      # Frontend assets
│   └── REST/
│       └── API.php           # REST endpoints
├── assets/
│   ├── css/
│   │   ├── frontend.css      # Frontend styles
│   │   └── admin.css         # Admin styles
│   └── js/
│       ├── frontend.js       # Frontend logic
│       └── admin.js          # Admin logic
└── README.md
```

## REST API Endpoints

### Get Wall Videos
```
GET /wp-json/vws/v1/walls/:id/videos
```

### Update Wall Videos
```
POST /wp-json/vws/v1/walls/:id/videos
Content-Type: application/json

["https://www.youtube.com/watch?v=...", ...]
```

### Get Wall Settings
```
GET /wp-json/vws/v1/walls/:id/settings
```

### List All Walls
```
GET /wp-json/vws/v1/walls
```

## Browser Support

- Chrome/Edge 88+
- Firefox 78+
- Safari 14+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance

- Lazy loading support for faster initial page loads
- Efficient CSS Grid layout
- YouTube IFrame API for optimal video performance
- Smooth scrolling with hardware acceleration
- No jQuery dependency on frontend

## Security

- Nonce verification on all forms
- Input sanitization and output escaping
- Capability-based access control
- XSS prevention
- CSRF protection

## Accessibility

- Keyboard navigation support
- Screen reader friendly
- WCAG 2.1 AA compliant
- Reduced motion support
- Proper heading hierarchy

## License

GPL v2 or later

## Support

For issues, feature requests, or contributions, please visit:
https://github.com/Captian-Rainbowbeard/video-wall-slider

## Changelog

### Version 1.0.0
- Initial release
- Core video wall functionality
- Admin dashboard
- REST API
- Responsive design
- Touch support
