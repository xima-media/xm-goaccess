INSERT INTO tx_xmgoaccess_domain_model_mapping (path, record_type, page, regex, title) VALUES
    ('\\/typo3.*', 2, 0, 1, 'TYPO3 backend'),
    ('\\/typo3temp\\/.*', 2, 0, 1, 'Temp files'),
    ('\\/server-status\\?auto.*', 2, 0, 1, 'Server status'),
    ('\\/fileadmin\\/.*', 2, 0, 1, 'fileadmin'),
    ('\\/cache_clear_random_.*', 2, 0, 1, 'Deployment: Cache clear'),
    ('\\/_assets\\/.*', 2, 0, 1, 'Assets');