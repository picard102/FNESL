import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  RangeControl,
  Spinner,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useMemo } from "@wordpress/element";
import { CareersList } from "../_shared/careers/CareersList";

registerBlockType( "fnesl/careers-section", {
  edit: ( { attributes, setAttributes } ) => {
    const { count } = attributes;

    const jobs = useSelect(
      ( select ) =>
        select( "core" ).getEntityRecords( "postType", "job", {
          per_page: 100,
          orderby: "menu_order",
          order: "asc",
          status: "publish",
          _fields: "id,title,meta,link",
        } ),
      [],
    );

    const visibleJobs = useMemo( () => {
      if ( ! Array.isArray( jobs ) ) return jobs;

      const today = new Date();
      today.setHours( 0, 0, 0, 0 );

      return jobs
        .filter( ( job ) => {
          const expiryDate =
            job.meta?.expiry_date || job.meta?.closing_date || "";

          if ( ! expiryDate ) return true;

          const expiry = new Date( `${ expiryDate }T23:59:59` );
          return ! Number.isNaN( expiry.getTime() ) && expiry >= today;
        } )
        .map( ( job ) => ( {
          id: job.id,
          title: job.title?.rendered || "",
          link: job.link || "#",
          jobType: job.meta?.job_type || "",
          location: job.meta?.job_location || "",
          salaryText: job.meta?.salary_text || "",
          closingDate: job.meta?.closing_date || "",
        } ) )
        .slice( 0, count );
    }, [ jobs, count ] );

    const blockProps = useBlockProps();

    return (
      <>
        <InspectorControls>
          <PanelBody title={ __( "Section Settings", "fnesl" ) } initialOpen={ true }>
            <RangeControl
              label={ __( "Jobs to show", "fnesl" ) }
              value={ count }
              min={ 1 }
              max={ 12 }
              onChange={ ( value ) => setAttributes( { count: value || 1 } ) }
            />
          </PanelBody>
        </InspectorControls>

        <div { ...blockProps }>
          { jobs === null ? (
            <div className="rounded-[1.5rem] border border-primary-200 bg-white px-4 py-6 text-center text-primary-700">
              <Spinner />
            </div>
          ) : (
            <CareersList jobs={ visibleJobs } />
          ) }
        </div>
      </>
    );
  },

  save: () => null,
} );
