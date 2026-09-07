# 🔧 Maintenance Guide — Morocco Explore

Internal notes for maintaining, updating, and troubleshooting the deployed application.

## 📍 Deployment Info

- **Host:** Railway
- **Live URL:** https://moroccoexplore-production.up.railway.app
- **Database:** MySQL (Railway-managed service)
- **File Storage:** Railway Volume mounted at `/app/storage/app/public` (persists uploaded/fetched images across deployments)
- **Repository:** https://github.com/RadiaBouzine/Morocco_Explore

## 💾 Database Backup

Run periodically from Railway Console (Morocco_Explore service → Console tab):

```bash
php artisan tinker --execute="echo App\Models\Destination::count() . ' destinations, ' . App\Models\User::count() . ' users';"
```

For a full SQL dump, use Railway's MySQL service → Data tab → Export, or connect with a MySQL client using the credentials in the MySQL service's Variables tab.

## 🖼️ Adding New Destinations

**Manually (recommended for real content):**
Go to `/admin/destinations` → "+ Add Destination" → fill form → upload image(s).

**In bulk (seeder):**
1. Edit `database/seeders/MoroccoDataSeeder.php`, add entries to the `$destinations` array.
2. Commit and push to GitHub (Railway auto-deploys).
3. In Railway Console: `php artisan db:seed --class=MoroccoDataSeeder --force`
4. To auto-fetch a photo: `php artisan destinations:fetch-images`

## 🔑 Key Environment Variables (Railway → Variables)

| Variable | Purpose |
|---|---|
| `APP_KEY` | Laravel encryption key — never change on a live app (breaks sessions/passwords) |
| `APP_URL` | Must include `https://` explicitly (Railway's `${{RAILWAY_PUBLIC_DOMAIN}}` does not include the protocol) |
| `DB_*` | Auto-linked to the MySQL service by Railway |
| `PEXELS_API_KEY` | Used by `php artisan destinations:fetch-images` |

## ⚠️ Known Issues & Fixes (things we hit during setup)

**CSS/JS loading as `http://` and getting blocked (mixed content):**
Fixed in `bootstrap/app.php` by adding `$middleware->trustProxies(at: '*');` — Railway sits behind a proxy, so Laravel needs to trust the `X-Forwarded-Proto` header to know the original request was HTTPS.

**Uploaded images disappearing after a redeploy:**
Railway containers are ephemeral — anything not on a persistent Volume is wiped on redeploy. Fixed by attaching a Railway Volume to `/app/storage/app/public`. If images ever disappear again, check Settings → the volume is still attached, then re-run `php artisan destinations:fetch-images`.

**Deactivated user accounts could still log in:**
Fixed in `app/Http/Requests/Auth/LoginRequest.php` — added an `is_active` check inside `authenticate()` that logs the user back out and shows an error if their account is deactivated.

**`config:clear` needed after changing `.env` variables in Railway:**
Railway's "Pre-deploy Command" is set to `php artisan config:clear` so cached config never goes stale after a variable change.

## 🔄 Updating Dependencies

```bash
composer update
npm update
npm run build
git add . && git commit -m "Update dependencies" && git push
```
Test locally with `php artisan serve` before pushing — Railway will auto-deploy from `main`.

## 🌐 SEO Checklist (already done, re-verify periodically)

- Google Search Console: verified via meta tag in `resources/views/welcome.blade.php`
- Sitemap: auto-generated at `/sitemap.xml`, submitted in Search Console → Sitemaps
- Check indexing progress: `site:moroccoexplore-production.up.railway.app` in Google

## 🆘 Quick Troubleshooting

| Symptom | Check |
|---|---|
| 500 error on live site | Railway → Deployments → View Logs |
| Images broken | Confirm Volume is attached + `php artisan storage:link` ran |
| Styles missing | Check `APP_URL` has `https://`, then `php artisan config:clear` |
| New destination not showing | Confirm `status = 'published'` in the destinations table |

