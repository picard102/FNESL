export function ProjectCard({ project }) {
  // project: { id, title, link, image, expertiseSlug, expertiseName }

  return (
    <a
      href={project.link}
      aria-label={project.title}
      className="group h-full overflow-hidden rounded-xs transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 bg-white text-white !no-underline ring-offset-primary-200  p-1 border border-primary-300 grid grid-rows-[1fr_.5fr] "
    >
      {/* Image */}
      <div className="relative overflow-hidden border border-primary-300 rounded-xs aspect-[6/4]">
        <img
          src={project.image}
          alt=""
          loading="lazy"
          decoding="async"
          className="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
        />
      </div>

      {/* Content */}
      <div className="p-3 flex flex-col isolate text-primary-600 items-start justify-items-start">
        {/* Expertise label */}
        {project.expertiseSlug && project.expertiseName && (
          <div className="flex items-center expertise-label isolate z-10 mt-2 mb-2">
            <svg
              className="aspect-square h-4 fill-current mr-2"
              aria-hidden="true"
            >
              <use xlinkHref={`#exp-${project.expertiseSlug}`} />
            </svg>
            <span className="text-xs pl-2 border-l">
              {project.expertiseName}
            </span>
          </div>
        )}

        <h3 className="text-balance text-xl font-medium leading-tight">
          {project.title}
        </h3>

        <p className="pt-6 text-sm text-primary-500 flex gap-3 items-center mt-auto ">
          <span className="bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
            <svg className="aspect-square h-3 fill-current" aria-hidden="true">
              <use xlinkHref="#icons_arrow_east" />
            </svg>
          </span>
          View Project
        </p>
      </div>
    </a>
  );
}
