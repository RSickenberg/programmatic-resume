<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class CustomerExperienceSpecialist extends BaseResume
{
    /**
     * @var array<string, list<string>>
     */
    private const array CLIENT_FACING_HIGHLIGHTS = [
        'Antistatique SA' => [
            'work.antistatique.highlight_5',
        ],
        'Ilem Group' => [
            'work.ilem.highlight_3',
        ],
    ];

    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.cx_specialist_position'),
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            url: new Url(self::URL),
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    /**
     * Adds the four IT employers with client-facing highlights only, a
     * standalone dated entry for the Securitas event-security work (kept
     * folded into the dateless breaks block for every other profile), and
     * the breaks block with the Securitas line removed to avoid repeating it.
     */
    public function addWorks(ResumeBuilder $builder): ResumeBuilder
    {
        $experiences = [];

        foreach ($this->getAllWorkExperiences()->get(WorkTypes::IT->value) as $work) {
            $experiences[] = new Work(
                name: $work->name,
                position: $work->position,
                location: $work->location,
                url: $work->url,
                startDate: $work->startDate,
                endDate: $work->endDate,
                summary: $work->summary,
                highlights: array_map(
                    fn(string $key): string => $this->trans($key),
                    self::CLIENT_FACING_HIGHLIGHTS[$work->name] ?? [],
                ),
            );
        }

        $experiences[] = new Work(
            name: 'Securitas AG',
            position: $this->trans('work.securitas.position'),
            location: 'Lausanne',
            startDate: '2023-07-01',
            endDate: '2024-03-31',
            highlights: [
                $this->trans('work.securitas.highlight_1'),
                $this->trans('work.securitas.highlight_2'),
                $this->trans('work.securitas.highlight_3'),
            ],
        );

        $experiences[] = new Work(
            name: '',
            position: $this->trans('work.breaks_and_side.position'),
            highlights: [
                $this->trans('work.breaks_and_side.highlight_travel'),
                $this->trans('work.breaks_and_side.highlight_military'),
            ],
        );

        return $this->addSortedWorks($builder, $experiences);
    }

    public function addSkills(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.cx_specialist_guest_relations_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        $this->trans('skills.cx_specialist_guest_welcoming'),
                        $this->trans('skills.cx_specialist_active_listening'),
                        $this->trans('skills.cx_specialist_multilingual_communication'),
                        $this->trans('skills.cx_specialist_technical_translation'),
                    ],
                ),
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.cx_specialist_composure_safety_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        $this->trans('skills.cx_specialist_composure_under_pressure'),
                        $this->trans('skills.cx_specialist_conflict_deescalation'),
                        $this->trans('skills.cx_specialist_access_control'),
                        $this->trans('skills.cx_specialist_attention_to_detail'),
                    ],
                ),
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.cx_specialist_professionalism_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        $this->trans('skills.cx_specialist_team_coordination'),
                        $this->trans('skills.cx_specialist_professionalism_brand'),
                        $this->trans('skills.cx_specialist_adaptability'),
                        $this->trans('skills.cx_specialist_discretion'),
                    ],
                ),
            );

        return $builder;
    }

    public function addProjects(ResumeBuilder $builder): ResumeBuilder
    {
        $builder->addProject(new Project(
            name: 'Tesla Companion',
            startDate: '2018-01-01',
            endDate: '2023-01-01',
            description: $this->trans('projects.tesla.description'),
            highlights: [
                $this->trans('projects.tesla.highlight_1'),
            ],
        ));

        return $builder;
    }

    protected function getSummary(): string
    {
        return implode("\n\n", [
            $this->trans('basics.cx_specialist_summary_role'),
            $this->trans('basics.cx_specialist_summary_experience'),
            $this->trans('basics.cx_specialist_summary_authorization'),
        ]);
    }

    /**
     * No coursework on this CV: apprenticeship modules carry no signal for a
     * showroom customer-experience role.
     *
     * @return list<string>
     */
    #[\Override]
    protected function getRelevantCourses(): array
    {
        return [];
    }
}
