describe('navbar', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <nav class="navbar">
                <button class="navbar-toggler" type="button"></button>
                <ul class="navbar-menu">
                    <li><a href="/">Harmonia</a></li>
                    <li><a href="/artiste">Artistes</a></li>
                </ul>
            </nav>
        `;
    });
});
