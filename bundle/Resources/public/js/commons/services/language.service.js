/**
 * Get the translation of a given key.
 *
 * @param string key, from language file.
 * @param string domain.
 */
export function __(key, domain = "codein_seo_toolkit", data = {}) {
  return Translator.trans(key, data, domain);
}
