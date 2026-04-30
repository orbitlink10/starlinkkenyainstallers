# Deployment

This Laravel app uses Vite for its frontend assets.

Before deploying to shared hosting:

1. Run `npm install` if dependencies are not present.
2. Run `npm run build`.
3. Upload the generated `public/build` directory with the rest of the application files.

If `public/build/manifest.json` is missing on the server, Laravel will throw `Illuminate\Foundation\ViteManifestNotFoundException`.
