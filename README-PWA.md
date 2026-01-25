# PWA Setup Guide

Your Expenses Tracker app is now configured as a Progressive Web App (PWA)! Users can install it on their devices for a native app-like experience.

## 📱 Installation Instructions

### For PC (Desktop)
1. Open the app in **Google Chrome**, **Microsoft Edge**, or **Brave**
2. Look for the **"Install App"** button in the sidebar
3. Or click the install icon in the browser's address bar
4. Click "Install" in the popup
5. The app will open in its own window and be added to your desktop/start menu

### For Android
1. Open the app in **Chrome** or **Samsung Internet**
2. Tap the **"Install App"** button in the sidebar
3. Or tap the three dots menu → "Add to Home Screen"
4. Tap "Add" or "Install"
5. The app icon will appear on your home screen

### For iOS (iPhone/iPad)
1. Open the app in **Safari**
2. Tap the **Share** button (square with arrow pointing up)
3. Scroll down and tap **"Add to Home Screen"**
4. Edit the name if desired and tap "Add"
5. The app icon will appear on your home screen

## 🎨 App Icons

To complete the PWA setup, you need to generate app icons. Follow these steps:

### Option 1: Using an Online Generator (Recommended)
1. Visit https://realfavicongenerator.net/
2. Upload a square image (512x512px recommended)
3. Download the generated icon package
4. Extract and copy the icons to `public/images/icons/`
5. Replace the placeholder files with these sizes:
   - icon-72x72.png
   - icon-96x96.png
   - icon-128x128.png
   - icon-144x144.png
   - icon-152x152.png
   - icon-192x192.png
   - icon-384x384.png
   - icon-512x512.png

### Option 2: Manual Creation
Use an image editor to create PNG icons in the sizes listed above.

## 📸 Screenshots (Optional)

For a better app store listing experience, add screenshots:
1. Take a desktop screenshot (1280x720px)
2. Take a mobile screenshot (540x720px)
3. Save them as:
   - `public/images/screenshots/desktop.png`
   - `public/images/screenshots/mobile.png`

## ✨ Features

Your PWA includes:
- ✅ Offline functionality with Service Worker
- ✅ Install prompt for easy installation
- ✅ Native app-like experience
- ✅ Home screen icon
- ✅ Splash screen (auto-generated)
- ✅ Works on PC, Android, and iOS

## 🔧 Configuration Files

- **`public/manifest.json`** - App manifest with metadata
- **`public/sw.js`** - Service Worker for offline functionality
- **`resources/views/partials/head.blade.php`** - PWA meta tags

## 🎯 Customization

Edit `public/manifest.json` to customize:
- `name` - Full app name
- `short_name` - Short name for home screen
- `description` - App description
- `theme_color` - Theme color (currently: #6366f1)
- `background_color` - Background color (currently: #18181b)

## 🚀 Testing

1. Run your app: `php artisan serve`
2. Open in Chrome: `http://localhost:8000`
3. Open DevTools (F12) → Application tab → Manifest
4. Check for any errors or warnings
5. Test the install prompt

## 📝 Notes

- PWA works best with HTTPS in production
- Service Worker caches are automatically updated
- Icons should be square and have transparency
- The install button appears automatically when the app is installable

Enjoy your Progressive Web App! 🎉
