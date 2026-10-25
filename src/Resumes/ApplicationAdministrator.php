<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Resumes;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use Romainsickenberg\ProgrammaticResume\Enums\WorkTypes;

/**
 * Application administrator and integrator profile, written against the
 * Loterie Romande offer "Administrateur·rice et intégrateur·rice d'applications"
 * (jobup.ch, published 2026-10-08).
 *
 * Only facts already on the other CVs, or stated by Romain, are used here.
 * Offer requirements with no source behind them (Power Automate, Azure,
 * Microsoft Entra ID, Windows) are left out on purpose rather than guessed.
 */
final class ApplicationAdministrator extends BaseResume
{
    #[\Override]
    public function basics(bool $hideSensitives): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.app_admin_position'),
            email: new Email(self::EMAIL),
            phone: $hideSensitives ? null : self::PHONE,
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    /**
     * Which highlights each employer shows on this CV, keyed by company name so
     * employer, title and dates keep coming from the single definition in
     * BaseResume. Each company keeps its own entry, as on the support CV: an ATS
     * rebuilds the employment history from them.
     *
     * Selection favours what the offer asks for: deployment and maintenance of
     * applications, version upgrades, monitoring, technical documentation,
     * working with business teams, security.
     *
     * Job titles are left exactly as held.
     *
     * @var array<string, list<string>>
     */
    private const array APP_ADMIN_HIGHLIGHTS = [
        'Academic Work SA' => [
            'work.app_admin_view.academic_work_directory',
            'work.support_view.academic_work_atlas',
            'work.support_view.academic_work_itsm',
            'work.academic_work.highlight_1',
        ],
        'Antistatique SA' => [
            'work.support_view.antistatique_operations',
            'work.antistatique.highlight_5',
            'work.antistatique.highlight_3',
        ],
        'Ilem Group' => [
            'work.ilem.highlight_1',
            'work.ilem.highlight_2',
            'work.ilem.highlight_4',
        ],
        'Liip AG' => [
            'work.liip.highlight_1',
            'work.liip.highlight_2',
        ],
    ];

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
                    self::APP_ADMIN_HIGHLIGHTS[$work->name] ?? [],
                ),
            );
        }

        array_push($experiences, ...$this->getAllWorkExperiences()->get(WorkTypes::BREAKS->value));

        return $this->addSortedWorks($builder, $experiences);
    }

    public function addSkills(ResumeBuilder $builder): ResumeBuilder
    {
        $builder
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.app_admin_microsoft_name'),
                    level: SkillLevel::Intermediate,
                    keywords: [
                        'Active Directory',
                        'Microsoft 365',
                        'SharePoint',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.tools_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Jira',
                        'Confluence',
                        'Easyvista',
                        'CMDB (Configuration Management Database)',
                        'Git',
                        'GitLab',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.cloud_devops_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Linux',
                        'Docker',
                        'GitHub Actions',
                        'CI/CD',
                        'AWS',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.backend_name'),
                    level: SkillLevel::Expert,
                    keywords: [
                        'PHP',
                        'Symfony',
                        'Laravel',
                        'Python',
                        'REST APIs',
                    ]
                )
            );

        return $builder;
    }

    protected function getSummary(): string
    {
        return implode("\n\n", [
            $this->trans('basics.app_admin_summary_role'),
            $this->trans('basics.app_admin_summary_experience'),
            $this->trans('basics.summary_authorization'),
        ]);
    }

    /**
     * Curated for system and application administration, rather than the
     * software-development curation of BaseResume's default.
     *
     * @return list<string>
     */
    #[\Override]
    protected function getRelevantCourses(): array
    {
        return [
            $this->trans('education.courses.os_administration'), // 305
            $this->trans('education.courses.server_services'), // 123
            $this->trans('education.courses.security_encryption'), // 114
            $this->trans('education.courses.app_security'), // 183
            $this->trans('education.courses.user_instruction'), // 214
            $this->trans('education.courses.scripting'), // 122
        ];
    }

    /**
     * Interests carry no ATS signal for this role and cost page space, as on the
     * backend CV.
     */
    #[\Override]
    public function addInterests(ResumeBuilder $builder): ResumeBuilder
    {
        return $builder;
    }
}
