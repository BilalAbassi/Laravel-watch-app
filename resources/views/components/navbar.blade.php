<header class="veltra-navbar-header">
    {{-- Left: Brand Logo & Wordmark --}}
    <div class="nav-brand">
        <a href="/" class="brand-link">
            <svg class="brand-logo" width="22" height="20" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Inverted Triangle -->
                <path d="M2.5 3.5H21.5L12 20.5L2.5 3.5Z" stroke="#6ea797" stroke-width="1.9" stroke-linejoin="round"/>
                <!-- Inner Geometric Horizontal Divider -->
                <line x1="6.2" y1="8.5" x2="17.8" y2="8.5" stroke="#6ea797" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <span class="brand-name">VELTRA</span>
        </a>
    </div>

    {{-- Center: Sculpted Metallic Tab with Navigation Links --}}
    <div class="nav-center">
        <div class="sculpted-tab-wrapper">
            <svg class="sculpted-tab-svg" viewBox="0 0 640 58" width="640" height="58" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- Outer Copper / Rose Gold Bevel Wing Gradient -->
                    <linearGradient id="copperWingGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#dd9c7c" />
                        <stop offset="18%" stop-color="#ca8666" />
                        <stop offset="50%" stop-color="#9a5a3f" />
                        <stop offset="82%" stop-color="#693722" />
                        <stop offset="100%" stop-color="#462214" />
                    </linearGradient>

                    <!-- Brushed Titanium / Gunmetal Body Vertical Gradient -->
                    <linearGradient id="titaniumGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#8e969e" />
                        <stop offset="8%" stop-color="#6b7279" />
                        <stop offset="28%" stop-color="#484f55" />
                        <stop offset="60%" stop-color="#2d3338" />
                        <stop offset="88%" stop-color="#1e2225" />
                        <stop offset="100%" stop-color="#14171a" />
                    </linearGradient>

                    <!-- Metallic Center Anisotropic Sheen Gradient -->
                    <linearGradient id="metallicSheenGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#ffffff" stop-opacity="0.01" />
                        <stop offset="22%" stop-color="#ffffff" stop-opacity="0.06" />
                        <stop offset="38%" stop-color="#ffffff" stop-opacity="0.18" />
                        <stop offset="50%" stop-color="#ffffff" stop-opacity="0.32" />
                        <stop offset="62%" stop-color="#ffffff" stop-opacity="0.18" />
                        <stop offset="78%" stop-color="#ffffff" stop-opacity="0.06" />
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0.01" />
                    </linearGradient>

                    <!-- Fine Horizontal Brushed Metal Texture -->
                    <pattern id="brushedTexture" width="120" height="2" patternUnits="userSpaceOnUse">
                        <line x1="0" y1="0.5" x2="120" y2="0.5" stroke="#ffffff" stroke-width="0.6" stroke-opacity="0.04" />
                    </pattern>

                    <!-- Top Edge Metallic Highlight -->
                    <linearGradient id="topHighlightGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="transparent" />
                        <stop offset="8%" stop-color="#8e969e" stop-opacity="0.4" />
                        <stop offset="30%" stop-color="#d4dbe2" stop-opacity="0.85" />
                        <stop offset="50%" stop-color="#ffffff" stop-opacity="1" />
                        <stop offset="70%" stop-color="#d4dbe2" stop-opacity="0.85" />
                        <stop offset="92%" stop-color="#8e969e" stop-opacity="0.4" />
                        <stop offset="100%" stop-color="transparent" />
                    </linearGradient>

                    <!-- Copper Flare Rim Highlight Left -->
                    <linearGradient id="copperRimLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f5be9f" />
                        <stop offset="45%" stop-color="#cb8665" />
                        <stop offset="100%" stop-color="#733b24" />
                    </linearGradient>

                    <!-- Copper Flare Rim Highlight Right -->
                    <linearGradient id="copperRimRight" x1="100%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#f5be9f" />
                        <stop offset="45%" stop-color="#cb8665" />
                        <stop offset="100%" stop-color="#733b24" />
                    </linearGradient>

                    <!-- Drop Shadow for the sculpted tab -->
                    <filter id="tabDropShadow" x="-10%" y="0%" width="120%" height="150%" filterUnits="userSpaceOnUse">
                        <feDropShadow dx="0" dy="8" stdDeviation="10" flood-color="#000000" flood-opacity="0.75" />
                        <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#000000" flood-opacity="0.5" />
                    </filter>
                </defs>

                <!-- LAYER 1: Drop Shadow & Copper Flare Wings Underlay -->
                <g filter="url(#tabDropShadow)">
                    <!-- Outer Copper Wing Foundation Path -->
                    <path d="M 0 0 
                             C 14 3, 25 12, 33 25
                             C 42 39, 50 51, 64 55
                             C 70 57, 76 58, 86 58
                             L 554 58
                             C 564 58, 570 57, 576 55
                             C 590 51, 598 39, 607 25
                             C 615 12, 626 3, 640 0
                             Z" 
                          fill="url(#copperWingGrad)" />
                    <!-- Glowing Copper Rim Edges -->
                    <path d="M 0 0 C 14 3, 25 12, 33 25 C 42 39, 50 51, 64 55" stroke="url(#copperRimLeft)" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M 640 0 C 626 3, 615 12, 607 25 C 598 39, 590 51, 576 55" stroke="url(#copperRimRight)" stroke-width="1.8" stroke-linecap="round" />
                </g>

                <!-- LAYER 2: Brushed Titanium Tab Body -->
                <!-- Curves smoothly inside copper flare, exposing the copper bevel shoulders -->
                <path d="M 20 0
                         C 30 5, 40 14, 48 27
                         C 56 40, 64 51, 76 55
                         C 82 57, 88 58, 98 58
                         L 542 58
                         C 552 58, 558 57, 564 55
                         C 576 51, 584 40, 592 27
                         C 600 14, 610 5, 620 0
                         Z" 
                      fill="url(#titaniumGrad)" />

                <!-- Metallic Anisotropic Sheen Overlay -->
                <path d="M 20 0
                         C 30 5, 40 14, 48 27
                         C 56 40, 64 51, 76 55
                         C 82 57, 88 58, 98 58
                         L 542 58
                         C 552 58, 558 57, 564 55
                         C 576 51, 584 40, 592 27
                         C 600 14, 610 5, 620 0
                         Z" 
                      fill="url(#metallicSheenGrad)" />

                <!-- Brushed Metal Texture Fill Overlay -->
                <path d="M 20 0
                         C 30 5, 40 14, 48 27
                         C 56 40, 64 51, 76 55
                         C 82 57, 88 58, 98 58
                         L 542 58
                         C 552 58, 558 57, 564 55
                         C 576 51, 584 40, 592 27
                         C 600 14, 610 5, 620 0
                         Z" 
                      fill="url(#brushedTexture)" />

                <!-- Fine Titanium Left & Right Bevel Edge Line -->
                <path d="M 20 0 C 30 5, 40 14, 48 27 C 56 40, 64 51, 76 55 C 82 57, 88 58, 98 58" 
                      stroke="rgba(255, 255, 255, 0.28)" stroke-width="1" />
                <path d="M 620 0 C 610 5, 600 14, 592 27 C 584 40, 576 51, 564 55 C 558 57, 552 58, 542 58" 
                      stroke="rgba(255, 255, 255, 0.18)" stroke-width="1" />

                <!-- Bottom Inset Accent Line -->
                <path d="M 98 58 L 542 58" stroke="#0e1113" stroke-width="1.2" />

                <!-- Top Edge Brushed Metallic Highlight Line -->
                <line x1="0" y1="0.8" x2="640" y2="0.8" stroke="url(#topHighlightGrad)" stroke-width="1.6" />

                <!-- LAYER 3: Navigation Links Embedded Inside ForeignObject -->
                <foreignObject x="0" y="0" width="640" height="58">
                    <div xmlns="http://www.w3.org/1999/xhtml" class="tab-content-container">
                        <nav class="tab-nav-menu">
                            <!-- Link 1: The Collection (Active) -->
                            <a href="#collection" class="tab-nav-item active">
                                <span class="tab-link-text">The Collection</span>
                                <span class="tab-active-indicator"></span>
                            </a>

                            <!-- Link 2: Brand Story -->
                            <a href="#story" class="tab-nav-item">
                                <span class="tab-link-text">Brand Story</span>
                            </a>

                            <!-- Link 3: About -->
                            <a href="#about" class="tab-nav-item">
                                <span class="tab-link-text">About</span>
                            </a>

                            <!-- Link 4: Journal -->
                            <a href="#journal" class="tab-nav-item">
                                <span class="tab-link-text">Journal</span>
                            </a>
                        </nav>
                    </div>
                </foreignObject>
            </svg>
        </div>
    </div>

    {{-- Right: Pill-Shaped Glass Dock with Icons --}}
    <div class="nav-actions">
        <div class="nav-capsule-dock">
            <!-- Search Icon -->
            <button type="button" class="dock-action-btn" aria-label="Search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#9ba2a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="10.5" cy="10.5" r="7.5"></circle>
                    <line x1="21" y1="21" x2="16" y2="16"></line>
                </svg>
            </button>

            <!-- User / Profile Icon -->
            <button type="button" class="dock-action-btn" aria-label="User Account">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#9ba2a8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>

            <!-- Shopping Bag Icon with Mint Badge -->
            <button type="button" class="dock-action-btn bag-btn" aria-label="Cart">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Rose Gold / Bronze Bag Handle -->
                    <path d="M10.5 8.5V7a3.5 3.5 0 0 1 7 0v1.5" stroke="#cb987e" stroke-width="1.6" stroke-linecap="round"/>
                    <!-- Rose Gold / Bronze Bag Body -->
                    <path d="M6 9H22L23.5 23.5H4.5L6 9Z" stroke="#cb987e" stroke-width="1.6" stroke-linejoin="round"/>
                    <!-- Mint Badge Circle -->
                    <circle cx="22" cy="6.5" r="5" fill="#5ea391"/>
                    <!-- Badge Number -->
                    <text x="22" y="9.4" fill="#0d1416" font-family="'Inter', sans-serif" font-size="9" font-weight="700" text-anchor="middle">2</text>
                </svg>
            </button>
        </div>
    </div>
</header>

<style>
/* ==========================================================================
   VELTRA LUXURY NAVBAR STYLING
   STRICT REQUIREMENT: NO position: absolute used anywhere!
   ========================================================================== */

/* Main Header Container */
.veltra-navbar-header {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: start;
    width: 100%;
    box-sizing: border-box;
    padding: 0 48px;
    background: transparent;
    user-select: none;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}

/* Left: Brand Logo & Wordmark */
.nav-brand {
    display: flex;
    align-items: center;
    justify-self: start;
    padding-top: 18px;
}

.brand-link {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: opacity 0.25s ease;
}

.brand-link:hover {
    opacity: 0.9;
}

.brand-logo {
    display: block;
    flex-shrink: 0;
    filter: drop-shadow(0 0 8px rgba(110, 167, 151, 0.3));
}

.brand-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 14.5px;
    font-weight: 400;
    letter-spacing: 0.46em;
    color: #cdd2d6;
    text-transform: uppercase;
}

/* Center: Sculpted Metallic Tab */
.nav-center {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    justify-self: center;
    margin-top: 0;
}

.sculpted-tab-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    line-height: 0;
}

.sculpted-tab-svg {
    display: block;
    overflow: visible;
}

/* Content Container inside foreignObject */
.tab-content-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    pointer-events: auto;
}

/* Navigation Menu */
.tab-nav-menu {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    padding: 0 70px;
    height: 100%;
    box-sizing: border-box;
}

/* Individual Navigation Link Item */
.tab-nav-item {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.25s ease;
    padding-top: 3px;
}

.tab-link-text {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 14.5px;
    font-weight: 450;
    color: #9ba1a7;
    letter-spacing: 0.015em;
    white-space: nowrap;
    transition: color 0.25s ease;
}

/* Hover State */
.tab-nav-item:hover .tab-link-text {
    color: #e2e6eb;
}

/* Active State: The Collection */
.tab-nav-item.active .tab-link-text {
    color: #cb987e;
    font-weight: 450;
}

/* Mint Green Active Underline */
.tab-active-indicator {
    display: block;
    width: 100%;
    height: 1.8px;
    background: #5e9585;
    border-radius: 1px;
    margin-top: 4px;
    box-shadow: 0 0 6px rgba(94, 149, 133, 0.35);
}

/* Right: Pill-Shaped Glass Dock */
.nav-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    justify-self: end;
    padding-top: 13px;
}

.nav-capsule-dock {
    display: inline-flex;
    align-items: center;
    gap: 20px;
    padding: 8px 18px;
    background: rgba(30, 36, 42, 0.72);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 9999px;
    box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.08), 0 6px 18px rgba(0, 0, 0, 0.45);
    transition: border-color 0.25s ease;
}

.nav-capsule-dock:hover {
    border-color: rgba(255, 255, 255, 0.14);
}

/* Dock Buttons */
.dock-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    padding: 0;
    margin: 0;
    cursor: pointer;
    outline: none;
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.dock-action-btn:hover {
    transform: scale(1.08);
}

.dock-action-btn svg {
    display: block;
}

.dock-action-btn:hover svg path,
.dock-action-btn:hover svg circle,
.dock-action-btn:hover svg line {
    stroke: #ffffff;
}

.dock-action-btn.bag-btn:hover svg path {
    stroke: #e4ab91;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .veltra-navbar-header {
        padding: 0 24px;
    }
    .tab-nav-menu {
        gap: 24px;
        padding: 0 50px;
    }
    .tab-link-text {
        font-size: 13px;
    }
    .brand-name {
        letter-spacing: 0.35em;
    }
}
</style>
