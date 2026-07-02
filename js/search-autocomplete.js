/**
 * IshahiyaOne Predictive Search Autocomplete (Enterprise Edition)
 * Works across all pages & subdirectory levels
 */
document.addEventListener('DOMContentLoaded', () => {
    const searchInputs = document.querySelectorAll('input[name="s"]');
    if (!searchInputs.length) return;

    // Dynamically find project root path
    const getBasePath = () => {
        const scripts = document.getElementsByTagName('script');
        for (let s of scripts) {
            if (s.src && s.src.includes('search-autocomplete.js')) {
                return s.src.split('js/search-autocomplete.js')[0];
            }
        }
        return window.location.pathname.includes('/ishahiyaone/') ? '/ishahiyaone/' : '/';
    };
    const basePath = getBasePath();

    searchInputs.forEach(input => {
        const form = input.closest('form');
        form.style.position = 'relative';

        // Disable browser default autocomplete
        input.setAttribute('autocomplete', 'off');

        const dropdown = document.createElement('div');
        dropdown.className = 'search-autocomplete-dropdown';
        dropdown.style.cssText = 'position:absolute;top:100%;left:0;right:0;background:#141414;border:1px solid #d4af37;border-radius:8px;box-shadow:0 12px 30px rgba(0,0,0,0.85);z-index:999999;max-height:420px;overflow-y:auto;display:none;margin-top:6px;text-align:left;';
        form.appendChild(dropdown);

        let debounceTimer = null;

        input.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            clearTimeout(debounceTimer);

            if (val.length < 2) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`${basePath}api/v1/search/autocomplete.php?q=${encodeURIComponent(val)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' && data.suggestions.length > 0) {
                            let html = '<div style="padding:8px 14px;font-size:10.5px;color:#d4af37;background:#1c1c1c;border-bottom:1px solid #2a2a2a;font-weight:800;text-transform:uppercase;letter-spacing:1px;display:flex;justify-content:space-between;"><span>Instant Suggestions</span><i class="fa-solid fa-bolt"></i></div>';
                            data.suggestions.forEach(item => {
                                html += `
                                <a href="${basePath}${item.url}" style="display:flex;align-items:center;padding:10px 14px;text-decoration:none;border-bottom:1px solid #222;transition:background 0.2s;color:#fff;">
                                    <img src="${basePath}${item.image}" style="width:42px;height:46px;object-fit:cover;border-radius:4px;margin-right:12px;border:1px solid #333;background:#222;" onerror="this.src='${basePath}shop_admin/uploads/no-image.png'">
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:12.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#eee;margin-bottom:3px;">${item.title}</div>
                                        <div style="font-size:12px;color:#d4af37;font-weight:800;">₹${item.price} <span style="color:#777;font-size:10.5px;font-weight:600;margin-left:6px;text-transform:uppercase;background:#222;padding:1px 5px;border-radius:3px;">${item.brand}</span></div>
                                    </div>
                                </a>`;
                            });
                            html += `<a href="${basePath}search.php?s=${encodeURIComponent(val)}" style="display:block;padding:10px;text-align:center;background:#1a1a1a;color:#d4af37;font-size:11.5px;font-weight:700;text-decoration:none;text-transform:uppercase;letter-spacing:1px;">View All Search Results <i class="fa-solid fa-arrow-right ml-1"></i></a>`;
                            dropdown.innerHTML = html;
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.style.display = 'none';
                        }
                    })
                    .catch(() => dropdown.style.display = 'none');
            }, 250);
        });

        document.addEventListener('click', (e) => {
            if (!form.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
});
