import { createRoot } from "@wordpress/element";
import { ProjectCard } from "../_shared/ui/ProjectCard";

function mountAll() {
  document.querySelectorAll("[data-home-hero-card]").forEach((el) => {
    try {
      const project = JSON.parse(el.getAttribute("data-project") || "null");
      if (!project) return;
      createRoot(el).render(<ProjectCard project={project} />);
    } catch {
      // malformed data — skip
    }
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountAll, { once: true });
} else {
  mountAll();
}
