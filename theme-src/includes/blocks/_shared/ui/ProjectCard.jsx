import { InlineSvg } from "./InlineSvg";

export function ProjectCardSkeleton() {
  return (
    <div className="h-full overflow-hidden rounded-xs p-1 border border-primary-300 grid grid-rows-[1fr_.5fr] animate-pulse">
      <div className="bg-primary-100 rounded-xs aspect-[6/4]" />
      <div className="p-3 flex flex-col gap-2">
        <div className="mt-2 h-3 w-1/2 rounded bg-primary-100" />
        <div className="h-4 w-4/5 rounded bg-primary-100" />
        <div className="h-4 w-3/5 rounded bg-primary-100" />
      </div>
    </div>
  );
}

export function ProjectCard({ project }) {
  const iconUrl = project?.expertiseIcon?.url || "";

  return (
    <a
      href={project.link}
      aria-label={project.title}
      className=" group overflow-hidden rounded-xs transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 bg-white text-white !no-underline ring-offset-primary-200 p-1 border border-primary-300 flex flex-col h-full
      "
    >
      <div className="relative overflow-hidden border border-primary-300 rounded-xs aspect-[6/4]">
        <img
          src={project.image}
          alt=""
          loading="lazy"
          decoding="async"
          className="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
        />
      </div>

      <div className="p-3 flex flex-col isolate text-primary-600 items-start justify-items-start">
        {iconUrl && project.expertiseName ? (
          <div className="flex items-center expertise-label isolate z-10 mt-2 mb-2">
            <InlineSvg url={iconUrl} className="h-4 w-4 mr-2 fill-current" />
            <span className="text-xs pl-2 border-l border-primary-200">
              {project.expertiseName}
            </span>
          </div>
        ) : null}

        <h3 className="text-balance text-xl font-medium leading-tight line-clamp-3">
          {project.title}
        </h3>
      </div>

      <p className="p-3 pt-6 text-sm text-primary-500 flex gap-3 items-center mt-auto">
        <span className="bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
          <svg className="aspect-square h-3 fill-current" aria-hidden="true">
            <use xlinkHref="#icons_arrow_east" />
          </svg>
        </span>
        View Project
      </p>
    </a>
  );
}
