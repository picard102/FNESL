export function ProjectCard({ project }) {
  // project: { id, title, link, image }
  return (
    <a
      className="group h-full overflow-hidden rounded-lg  transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 grid grid-cols-1 grid-rows-2 border border-primary-300 bg-primary-400 text-white !no-underline aspect-[8/12] "
      href={project.link}
      aria-label={project.title}
    >
      <img
        className="relative  col-start-1 row-start-1 row-end-3  overflow-hidden  mask-alpha mask-b-from-70% mask-b-to-100% h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
        src={project.image}
        alt=""
        loading="lazy"
        decoding="async"
      />
      <div className="p-6 col-start-1 row-start-2 row-end-2 flex flex-col isolate justify-end bg-gradient-to-t from-primary-900/40 to-black/0 text-white ">
        <h3 className="m-0 text-base font-medium leading-snug ">
          {project.title}
        </h3>
      </div>
    </a>
  );
}