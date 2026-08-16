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
    public function addLanguages(ResumeBuilder $builder): void;

    /**
     * Add works related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addWorks(ResumeBuilder $builder): void;

    /**
     * Add works related skills to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addSkills(ResumeBuilder $builder): void;

    /**
     * Add awards related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addAwards(ResumeBuilder $builder): void;

    /**
     * Add interests related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addInterests(ResumeBuilder $builder): void;

    /**
     * Add projects related to the current position.
     * @param \JustSteveKing\Resume\Builders\ResumeBuilder $builder Attach the builder of the resume.
     */
    public function addProjects(ResumeBuilder $builder): void;
}
