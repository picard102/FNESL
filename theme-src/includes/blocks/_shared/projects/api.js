import apiFetch from "@wordpress/api-fetch";

export const PROJECT_ARCHIVE_ENDPOINT = "/fnesl/v1/project-archive";

export async function fetchProjectArchive({
  page = 1,
  perPage = 12,
  mode = "and",
  terms = {},
  show = [],
  includeFilters = false,
} = {}) {
  return apiFetch({
    path: PROJECT_ARCHIVE_ENDPOINT,
    method: "POST",
    data: { page, perPage, mode, terms, show, includeFilters },
  });
}
