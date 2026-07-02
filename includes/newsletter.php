<!-- Premium Newsletter Section -->
<div class="footer-newsletter">
    <div class="newsletter-inner">
        <div class="newsletter-text">
            <h4><i class="fa-regular fa-envelope"></i> Sign up for Newsletter</h4>
            <p>Get email updates about our latest shop and <span class="text-gold">special offers</span></p>
        </div>
        <form class="newsletter-form" id="global-newsletter-form">
            <input type="email" id="global-newsletter-email" placeholder="Your email address" required>
            <button type="submit" id="global-newsletter-btn">Subscribe</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('global-newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('global-newsletter-email');
            const btn = document.getElementById('global-newsletter-btn');
            const originalText = btn.innerHTML;
            const email = emailInput.value.trim();
            
            if(email) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
                btn.disabled = true;
                
                fetch('subscribe_newsletter.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Subscribed Successfully!',
                                text: 'Thank you for subscribing to our newsletter.',
                                showConfirmButton: false,
                                timer: 3000,
                                background: '#111',
                                color: '#d4af37'
                            });
                        } else {
                            alert('Thank you for subscribing!');
                        }
                        emailInput.value = '';
                        btn.innerHTML = '<i class="fas fa-check"></i> Subscribed';
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Notice',
                                text: data.message,
                                background: '#111',
                                color: '#fff'
                            });
                        } else {
                            alert(data.message);
                        }
                        btn.innerHTML = originalText;
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.innerHTML = originalText;
                })
                .finally(() => {
                    btn.disabled = false;
                    setTimeout(() => {
                        if (btn.innerHTML.includes('Subscribed')) {
                            btn.innerHTML = originalText;
                        }
                    }, 3000);
                });
            }
        });
    }
});
</script>
