/*
 * The page works without this file: every link and image is plain HTML.
 * It adds three conveniences — a theme switch, a screenshot lightbox, and
 * "View the code" links that point at GitHub once REPO_URL is set.
 */
(function () {
	'use strict';

	// Where the code lives on GitHub. Left empty, "View the code" opens each
	// project's README beside this page instead.
	var REPO_URL = 'https://github.com/AyaanSameer/AyaanSameer.github.io';
	var BRANCH = 'main';

	/* ---------- Theme ---------- */
	var root = document.documentElement;
	var toggle = document.getElementById('theme-toggle');

	function currentTheme() {
		if (root.dataset.theme) return root.dataset.theme;
		return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
	}

	function labelToggle() {
		if (!toggle) return;
		toggle.setAttribute('aria-label', currentTheme() === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
	}

	if (toggle) {
		labelToggle();
		toggle.addEventListener('click', function () {
			var next = currentTheme() === 'dark' ? 'light' : 'dark';
			root.dataset.theme = next;
			try { localStorage.setItem('theme', next); } catch (e) { /* private mode: the choice lasts this visit */ }
			labelToggle();
		});
	}

	/* ---------- Code links ---------- */
	if (REPO_URL) {
		document.querySelectorAll('[data-repo-path]').forEach(function (a) {
			a.href = REPO_URL.replace(/\/$/, '') + '/tree/' + BRANCH + '/' + a.getAttribute('data-repo-path');
			a.target = '_blank';
			a.rel = 'noopener';
		});
	}

	/* ---------- Lightbox ---------- */
	var dialog = document.getElementById('lightbox');
	var img = document.getElementById('lightbox-img');
	var caption = document.getElementById('lightbox-caption');
	var opener = null;

	if (dialog && typeof dialog.showModal === 'function') {
		document.querySelectorAll('.shot-btn[data-full]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var thumb = btn.querySelector('img');
				opener = btn;
				img.src = btn.getAttribute('data-full');
				img.alt = thumb ? thumb.alt : '';
				caption.textContent = btn.getAttribute('data-caption') || '';
				dialog.showModal();
			});
		});

		// A click on the dark backdrop (the dialog itself, not its picture) closes it.
		dialog.addEventListener('click', function (event) {
			if (event.target === dialog) dialog.close();
		});

		// Focus goes back to the screenshot that opened it.
		dialog.addEventListener('close', function () {
			if (opener) opener.focus();
		});
	} else {
		// No <dialog> support: open the full image in the same tab instead.
		document.querySelectorAll('.shot-btn[data-full]').forEach(function (btn) {
			btn.addEventListener('click', function () {
				window.location.href = btn.getAttribute('data-full');
			});
		});
	}

	/* ---------- Footer year ---------- */
	var year = document.getElementById('year');
	if (year) year.textContent = String(new Date().getFullYear());
})();
