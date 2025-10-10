/**
 * Loader Management System
 * Controla a exibição e ocultação do loader na aplicação
 */

class LoaderManager {
    constructor() {
        this.loader = null;
        this.isVisible = false;
        this.minDisplayTime = 500; // Tempo mínimo de exibição em ms
        this.showStartTime = null;
        this.init();
    }

    init() {
        // Criar o elemento do loader se não existir
        this.createLoader();
        
        // Configurar eventos
        this.setupEvents();
        
        // Mostrar loader inicial se a página ainda estiver carregando
        if (document.readyState === 'loading') {
            this.show('Carregando página...');
        }
    }

    createLoader() {
        // Verificar se o loader já existe
        if (document.getElementById('app-loader')) {
            this.loader = document.getElementById('app-loader');
            return;
        }

        // Criar estrutura do loader
        const loaderHTML = `
            <div id="app-loader" class="loader-overlay hidden">
                <div class="loader-container">
                    <div class="loader-spinner"></div>
                    <div class="loader-text" id="loader-text">Carregando...</div>
                    <div class="loader-dots">
                        <div class="loader-dot"></div>
                        <div class="loader-dot"></div>
                        <div class="loader-dot"></div>
                    </div>
                </div>
            </div>
        `;

        // Adicionar ao body
        document.body.insertAdjacentHTML('beforeend', loaderHTML);
        this.loader = document.getElementById('app-loader');
    }

    show(message = 'Carregando...') {
        if (!this.loader) return;

        this.showStartTime = Date.now();
        this.isVisible = true;

        // Atualizar texto
        const textElement = document.getElementById('loader-text');
        if (textElement) {
            textElement.textContent = message;
        }

        // Mostrar loader
        this.loader.classList.remove('hidden');
        
        // Adicionar classe ao body para prevenir scroll
        document.body.style.overflow = 'hidden';
    }

    hide() {
        if (!this.loader || !this.isVisible) return;

        const hideLoader = () => {
            this.loader.classList.add('hidden');
            this.isVisible = false;
            
            // Restaurar scroll do body
            document.body.style.overflow = '';
        };

        // Garantir tempo mínimo de exibição
        if (this.showStartTime) {
            const elapsedTime = Date.now() - this.showStartTime;
            const remainingTime = Math.max(0, this.minDisplayTime - elapsedTime);
            
            setTimeout(hideLoader, remainingTime);
        } else {
            hideLoader();
        }
    }

    setupEvents() {
        // Ocultar loader quando a página terminar de carregar
        window.addEventListener('load', () => {
            setTimeout(() => this.hide(), 100);
        });

        // Mostrar loader em navegação (se suportado)
        if ('navigation' in window) {
            window.navigation.addEventListener('navigate', () => {
                this.show('Navegando...');
            });
        }

        // Interceptar formulários para mostrar loader
        document.addEventListener('submit', (e) => {
            if (e.target.tagName === 'FORM') {
                this.show('Processando...');
            }
        });

        // Interceptar links para mostrar loader
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && !link.download) {
                // Verificar se é um link interno
                const url = new URL(link.href, window.location.origin);
                if (url.origin === window.location.origin) {
                    this.show('Carregando página...');
                }
            }
        });

        // Ocultar loader se houver erro na navegação
        window.addEventListener('beforeunload', () => {
            this.hide();
        });
    }

    // Métodos públicos para controle manual
    showWithMessage(message) {
        this.show(message);
    }

    hideLoader() {
        this.hide();
    }

    isLoaderVisible() {
        return this.isVisible;
    }
}

// Inicializar o loader quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.loaderManager = new LoaderManager();
});

// Funções globais para facilitar o uso
window.showLoader = function(message = 'Carregando...') {
    if (window.loaderManager) {
        window.loaderManager.showWithMessage(message);
    }
};

window.hideLoader = function() {
    if (window.loaderManager) {
        window.loaderManager.hideLoader();
    }
};

// Mostrar loader para requisições AJAX/Fetch
if (window.fetch) {
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        window.showLoader('Carregando dados...');
        
        return originalFetch.apply(this, args)
            .then(response => {
                window.hideLoader();
                return response;
            })
            .catch(error => {
                window.hideLoader();
                throw error;
            });
    };
}

// Interceptar XMLHttpRequest
if (window.XMLHttpRequest) {
    const originalOpen = XMLHttpRequest.prototype.open;
    const originalSend = XMLHttpRequest.prototype.send;
    
    XMLHttpRequest.prototype.open = function(...args) {
        this._url = args[1];
        return originalOpen.apply(this, args);
    };
    
    XMLHttpRequest.prototype.send = function(...args) {
        window.showLoader('Carregando dados...');
        
        this.addEventListener('loadend', () => {
            window.hideLoader();
        });
        
        return originalSend.apply(this, args);
    };
}