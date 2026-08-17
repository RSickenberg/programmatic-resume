<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;

interface AbstractResume
{
    public function __invoke();

    public function basics(): Basics;

    /**
     * Add languages.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addLanguages(ResumeBuilder $builder): ResumeBuilder;

    /**
     * Add works related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addWorks(ResumeBuilder $builder): ResumeBuilder;

    /**
     * Add works related skills to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addSkills(ResumeBuilder $builder): ResumeBuilder;

    /**
     * Add awards related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addAwards(ResumeBuilder $builder): ResumeBuilder;

    /**
     * Add interests related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addInterests(ResumeBuilder $builder): ResumeBuilder;

    /**
     * Add projects related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addProjects(ResumeBuilder $builder): ResumeBuilder;
}
