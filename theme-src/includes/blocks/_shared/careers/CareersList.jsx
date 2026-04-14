import { __ } from "@wordpress/i18n";

export const formatCareersDate = ( value ) => {
  if ( ! value ) return "";

  const date = new Date( `${ value }T12:00:00` );
  if ( Number.isNaN( date.getTime() ) ) return value;

  return date.toLocaleDateString( "en-CA", {
    month: "short",
    day: "numeric",
    year: "numeric",
  } );
};

export function CareersList( {
  jobs,
  emptyMessage = __( "No active job postings found.", "fnesl" ),
} ) {
  if ( ! Array.isArray( jobs ) || jobs.length === 0 ) {
    return (
      <div className="rounded-sm border border-dashed border-primary-200 bg-white px-4 py-6 text-sm text-primary-700">
        { emptyMessage }
      </div>
    );
  }

  return (
    <div className="grid gap-4">
      { jobs.map( ( job ) => (
        <article
          key={ job.id }
          className="rounded-sm border border-primary-100 bg-white px-5 py-5 text-primary-900  md:px-6"
        >
          <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div className="min-w-0 flex-1">
              <div className="flex flex-wrap items-center gap-3">
                <h3
                  className="text-md font-medium leading-tight text-primary-950"
                  dangerouslySetInnerHTML={ { __html: job.title || "" } }
                />

                { job.jobType && (
                  <span className="rounded-full border border-accent-200 bg-[color:var(--wp--preset--color--accent-200)]/35 px-3 py-1 text-xs font-medium text-accent-700">
                    { job.jobType }
                  </span>
                ) }
              </div>

              <div className="mt-3 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-primary-700">
                { job.location && <span>{ job.location }</span> }

                { job.location && job.salaryText && (
                  <span className="text-primary-400">•</span>
                ) }

                { job.salaryText && <span>{ job.salaryText }</span> }

                { job.closingDate && (
                  <>
                    { ( job.location || job.salaryText ) && (
                      <span className="text-primary-400">•</span>
                    ) }
                    <span>
                      { __( "Closes", "fnesl" ) } { formatCareersDate( job.closingDate ) }
                    </span>
                  </>
                ) }
              </div>
            </div>

            <div className="shrink-0">
              <a
                href={ job.link || "#" }
                className="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white px-5 py-3 text-sm font-medium text-primary-900 no-underline hover:no-underline focus:no-underline shadow-[0_2px_10px_rgba(48,89,110,0.06)] transition hover:border-primary-400 hover:bg-primary-50"
              >
                { __( "Read More", "fnesl" ) }
                <svg className="h-3 w-3 fill-current" aria-hidden="true">
                  <use xlinkHref="#icons_arrow_east"></use>
                </svg>
              </a>
            </div>
          </div>
        </article>
      ) ) }
    </div>
  );
}
