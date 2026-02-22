import React, { useEffect, useMemo, useRef } from 'react';
import { createRoot } from 'react-dom/client';
import LiquidGlass from 'liquid-glass-react';

function FormShell({ backgroundUrl, html }) {
    const containerRef = useRef(null);

    const shellStyle = useMemo(() => ({
        '--idea-bg': `url("${backgroundUrl}")`,
    }), [backgroundUrl]);

    useEffect(() => {
        const description = document.getElementById('description');
        const descriptionCount = document.getElementById('description-count');
        const documents = document.getElementById('documents');
        const documentsFeedback = document.getElementById('documents-feedback');

        if (description && descriptionCount) {
            const updateCount = () => {
                const count = description.value.trim().length;
                descriptionCount.textContent = `${count} character${count === 1 ? '' : 's'}`;
            };

            updateCount();
            description.addEventListener('input', updateCount);
        }

        if (documents && documentsFeedback) {
            const updateFiles = () => {
                const files = Array.from(documents.files || []);

                if (files.length === 0) {
                    documentsFeedback.textContent = 'Optional. Up to 10MB per file. Allowed: PDF, Office files, and images.';
                    return;
                }

                const fileNames = files.map((file) => file.name);
                const listed = fileNames.slice(0, 2).join(', ');
                const remainder = files.length > 2 ? ` +${files.length - 2} more` : '';

                documentsFeedback.textContent = `Selected: ${listed}${remainder}`;
            };

            updateFiles();
            documents.addEventListener('change', updateFiles);
        }
    }, []);

    return (
        <section className="idea-create-shell" style={shellStyle} ref={containerRef}>
            <div className="container">
                <LiquidGlass
                    mouseContainer={containerRef}
                    displacementScale={96}
                    blurAmount={0.08}
                    saturation={140}
                    aberrationIntensity={1.8}
                    elasticity={0.28}
                    cornerRadius={32}
                    padding="0px"
                    className="idea-liquid-glass"
                >
                    <div className="idea-liquid-inner" dangerouslySetInnerHTML={{ __html: html }} />
                </LiquidGlass>
            </div>
        </section>
    );
}

(function mountIdeaCreate() {
    const rootElement = document.getElementById('idea-create-liquid-root');
    const template = document.getElementById('idea-create-form-template');

    if (!rootElement || !template) {
        return;
    }

    const backgroundUrl = rootElement.dataset.backgroundUrl || '';
    const html = template.innerHTML;

    try {
        createRoot(rootElement).render(<FormShell backgroundUrl={backgroundUrl} html={html} />);
    } catch (error) {
        console.error('Liquid glass mount failed:', error);
        rootElement.innerHTML = html;
        rootElement.classList.add('container', 'py-4');
    }
})();
