import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { SelectControl, Spinner, Notice } from '@wordpress/components';
import { useCallback, useEffect, useMemo, useState, Fragment } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

const editorData = window.wpLanguagesData || {};
const languages = editorData.languages || {};
const allCpt = editorData.allCpt || [];

console.log('WP Languages Editor: Data loaded', { languages, allCpt, editorData });

if (editorData.nonce && !window.wpLanguagesNonceApplied) {
    apiFetch.use(apiFetch.createNonceMiddleware(editorData.nonce));
    window.wpLanguagesNonceApplied = true;
}

const getContextForPostType = (postType) => {
    if (!postType || !Array.isArray(allCpt)) {
        return null;
    }

    let foundGroup = null;
    let sourceLang = null;

    allCpt.some((group) => {
        if (!group) {
            return false;
        }

        return Object.keys(group).some((langKey) => {
            if (group[langKey] === postType) {
                foundGroup = group;
                sourceLang = langKey;
                return true;
            }
            return false;
        });
    });

    if (!foundGroup || !sourceLang) {
        return null;
    }

    const otherLangs = Object.keys(foundGroup).filter((langKey) => langKey !== sourceLang);

    return {
        group: foundGroup,
        sourceLang,
        otherLangs,
    };
};

const ConnectionsPanel = () => {
    const postId = useSelect((select) => select('core/editor').getCurrentPostId(), []);
    const postType = useSelect((select) => select('core/editor').getCurrentPostType(), []);

    const context = useMemo(() => getContextForPostType(postType), [postType]);
    const [selectedLang, setSelectedLang] = useState('');
    const [connections, setConnections] = useState({});
    const [connectionsLoading, setConnectionsLoading] = useState(false);
    const [availablePosts, setAvailablePosts] = useState([]);
    const [availableLoading, setAvailableLoading] = useState(false);
    const [actionLoading, setActionLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (!context) {
            setSelectedLang('');
            setConnections({});
            setAvailablePosts([]);
            return;
        }

        if (context.otherLangs.includes(selectedLang)) {
            return;
        }

        setSelectedLang(context.otherLangs.length ? context.otherLangs[0] : '');
    }, [context]);

    const loadConnections = useCallback(() => {
        if (!postId || !context) {
            setConnections({});
            return;
        }

        setConnectionsLoading(true);
        setError(null);

        apiFetch({ path: `/wp-languages/v1/connections?post_id=${postId}` })
            .then((response) => {
                setConnections(response?.connections || {});
            })
            .catch((err) => {
                const message = err?.message || err?.data?.message || 'Error al cargar las conexiones.';
                setError(message);
            })
            .finally(() => {
                setConnectionsLoading(false);
            });
    }, [postId, context]);

    useEffect(() => {
        if (!postId || !context) {
            return;
        }
        loadConnections();
    }, [postId, context, loadConnections]);

    const loadAvailable = useCallback(
        (langKey) => {
            if (!postId || !context || !langKey) {
                setAvailablePosts([]);
                return;
            }

            setAvailableLoading(true);
            setError(null);

            apiFetch({ path: `/wp-languages/v1/available?post_id=${postId}&target_lang=${langKey}` })
                .then((response) => {
                    setAvailablePosts(response?.posts || []);
                })
                .catch((err) => {
                    const message = err?.message || err?.data?.message || 'Error al cargar los posts disponibles.';
                    setError(message);
                })
                .finally(() => {
                    setAvailableLoading(false);
                });
        },
        [postId, context]
    );

    useEffect(() => {
        if (!selectedLang) {
            setAvailablePosts([]);
            return;
        }
        loadAvailable(selectedLang);
    }, [selectedLang, loadAvailable]);

    const handleLanguageChange = (value) => {
        setSelectedLang(value);
        setAvailablePosts([]);
    };

    const handlePostChange = (value) => {
        if (!selectedLang || !postId) {
            return;
        }

        const targetId = value ? parseInt(value, 10) : 0;

        setActionLoading(true);
        setError(null);

        apiFetch({
            path: '/wp-languages/v1/connect',
            method: 'POST',
            data: {
                post_id: postId,
                target_lang: selectedLang,
                target_post_id: targetId,
            },
        })
            .then((response) => {
                setConnections(response?.connections || {});
                loadAvailable(selectedLang);
            })
            .catch((err) => {
                const message = err?.message || err?.data?.message || 'Error al guardar la conexión.';
                setError(message);
            })
            .finally(() => {
                setActionLoading(false);
            });
    };

    if (!context) {
        return (
            <PluginDocumentSettingPanel
                name="wp-languages-connections"
                title="Conexiones de idiomas"
            >
                <p>Este tipo de contenido no admite conexiones entre idiomas.</p>
            </PluginDocumentSettingPanel>
        );
    }

    if (!context.otherLangs.length) {
        return (
            <PluginDocumentSettingPanel
                name="wp-languages-connections"
                title="Conexiones de idiomas"
            >
                <p>No hay otros idiomas configurados para este tipo de contenido.</p>
            </PluginDocumentSettingPanel>
        );
    }

    const languageOptions = context.otherLangs.map((langKey) => ({
        value: langKey,
        label: languages[langKey] || langKey,
    }));

    const postsOptions = [
        {
            value: '',
            label: availableLoading ? 'Cargando opciones…' : '— Sin conexión —',
        },
        ...availablePosts.map((item) => ({
            value: String(item.id),
            label: item.title
                ? `${item.title}${item.status && item.status !== 'publish' ? ` (${item.status})` : ''}`
                : `(ID ${item.id})`,
        })),
    ];

    const selectedPostId = selectedLang && connections[selectedLang]?.id
        ? String(connections[selectedLang].id)
        : '';

    return (
        <PluginDocumentSettingPanel
            name="wp-languages-connections"
            title="Conexiones de idiomas"
        >
            {error && (
                <Notice status="error" onRemove={() => setError(null)} isDismissible>
                    {error}
                </Notice>
            )}

            <SelectControl
                label="Idioma destino"
                value={selectedLang}
                onChange={handleLanguageChange}
                options={[
                    { value: '', label: 'Selecciona un idioma' },
                    ...languageOptions,
                ]}
            />

            {connectionsLoading && <Spinner />}

            {selectedLang ? (
                availableLoading ? (
                    <Spinner />
                ) : (
                    <Fragment>
                        <SelectControl
                            label="Post conectado"
                            value={selectedPostId}
                            onChange={handlePostChange}
                            options={postsOptions}
                            help="Solo se muestran los posts que aún no están emparejados."
                            disabled={actionLoading}
                        />
                        {!availablePosts.length && !selectedPostId && (
                            <p>No hay posts disponibles para este idioma.</p>
                        )}
                    </Fragment>
                )
            ) : (
                <p>Elige primero un idioma para ver los posts disponibles.</p>
            )}
        </PluginDocumentSettingPanel>
    );
};

registerPlugin('wp-languages-connections', {
    render: ConnectionsPanel,
    icon: 'translation',
});

console.log('WP Languages Editor: Plugin registered');