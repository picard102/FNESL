import { createRoot } from "@wordpress/element";
import { CareersList } from "../_shared/careers/CareersList";

function safeParseJsonAttr( el, attrName ) {
  try {
    return JSON.parse( el.getAttribute( attrName ) || "{}" );
  } catch {
    return {};
  }
}

function App( { config } ) {
  return (
    <div>
      <CareersList jobs={ config.jobs || [] } />
    </div>
  );
}

function mountAll() {
  document.querySelectorAll( "[data-careers-section]" ).forEach( ( el ) => {
    const config = safeParseJsonAttr( el, "data-config" );
    createRoot( el ).render( <App config={ config } /> );
  } );
}

if ( document.readyState === "loading" ) {
  document.addEventListener( "DOMContentLoaded", mountAll, { once: true } );
} else {
  mountAll();
}
