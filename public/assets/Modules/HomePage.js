// home.js
export class HomePage {
    constructor() {
        this.statsNumbers = document.querySelectorAll('.stat-number');
        this.tabButtons = document.querySelectorAll('.activity-tab');
        this.tabsContent = document.querySelectorAll('.tab-content');
        this.init();
    }

    init() {
        this.animateStats();
        this.initTabs();
        this.initSmoothScroll();
    }

    animateStats() {
        this.statsNumbers.forEach(stat => {
            const target = parseInt(stat.dataset.target);
            let count = 0;
            const increment = Math.ceil(target / 100);
            const interval = setInterval(() => {
                count += increment;
                if (count >= target) {
                    stat.textContent = target;
                    clearInterval(interval);
                } else {
                    stat.textContent = count;
                }
            }, 20);
        });
    }

    initTabs() {
        this.tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const tab = button.dataset.tab;
                this.tabsContent.forEach(content => {
                    content.style.display = content.id === `${tab}-content` ? 'block' : 'none';
                });
            });
        });
    }

    initSmoothScroll() {
        const scrollLinks = document.querySelectorAll('a[href^="#"]');
        scrollLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetEl = document.getElementById(targetId);
                if (targetEl) {
                    targetEl.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }
}
