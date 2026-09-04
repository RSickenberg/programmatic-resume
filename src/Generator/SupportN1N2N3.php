<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class SupportN1N2N3 extends BaseResume
{
    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.support_position'),
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    /**
     * Which highlights each employer shows on the support CV, keyed by company
     * name so employer, title and dates keep coming from the single definition
     * in BaseResume and cannot drift.
     *
     * Folding the four employers into one entry hid employer, role and period
     * from a parser, which is exactly what an ATS reconstructs an employment
     * history from. Each company keeps its own entry; only the selection of
     * highlights changes, favouring the support-relevant work (device fleet
     * rollout, incident response, server operations, security hardening).
     *
     * Job titles are left exactly as held. Rewriting them to read as support
     * roles would match more keywords and would be a lie.
     *
     * @var array<string, list<string>>
     */
    private const array SUPPORT_HIGHLIGHTS = [
        'Academic Work SA' => [
            'work.support_view.academic_work_atlas',
            'work.support_view.academic_work_itsm',
            'work.academic_work.highlight_2',
        ],
        'Antistatique SA' => [
            'work.support_view.antistatique_operations',
            'work.antistatique.highlight_4',
            'work.antistatique.highlight_5',
        ],
        'Ilem Group' => [
            'work.ilem.highlight_2',
            'work.ilem.highlight_1',
            'work.ilem.highlight_3',
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
                    self::SUPPORT_HIGHLIGHTS[$work->name] ?? [],
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
                    name: $this->trans('skills.support_os_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Windows 10 / 11',
                        'Windows Server',
                        'macOS',
                        $this->trans('skills.support_os_linux_advanced'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_directory_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Active Directory',
                        'Microsoft Entra ID (Azure AD)',
                        'Group Policy (GPO)',
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_ticketing_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Easyvista',
                        'Jira',
                        'CMDB (Configuration Management Database)',
                        $this->trans('skills.support_ticketing_itil'),
                        $this->trans('skills.support_ticketing_incident_mgmt'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_networking_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'TCP/IP',
                        'DNS & DHCP',
                        'VPN',
                        $this->trans('skills.support_networking_wifi_troubleshooting_network_setup'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_hardware_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        $this->trans('skills.support_hardware_workstation_troubleshooting'),
                        'Mobile Device Management (MDM)',
                        $this->trans('skills.support_hardware_printer_support'),
                    ]
                )
            )
            ->addSkill(
                new Skill(
                    name: $this->trans('skills.support_collaboration_name'),
                    level: SkillLevel::Advanced,
                    keywords: [
                        'Microsoft 365',
                        'Outlook',
                        'Teams',
                        'SharePoint',
                        $this->trans('skills.support_collaboration_office_tools'),
                        $this->trans('skills.support_collaboration_office_advanced'),
                        $this->trans('skills.support_collaboration_remote_tools'),
                    ]
                )
            );

        return $builder;
    }

    protected function getSummary(): string
    {
        return implode("\n\n", [
            $this->trans('basics.support_summary_role'),
            $this->trans('basics.support_summary_experience'),
            $this->trans('basics.summary_authorization'),
        ]);
    }

    /**
     * Curated for relevance to a general IT Support / hardware profile,
     * rather than the software-development curation used by BaseResume's
     * default (see there for the full list of excluded modules).
     */
    #[\Override]
    protected function getRelevantCourses(): array
    {
        return [
            $this->trans('education.courses.small_business_it_infra'), // 117
            $this->trans('education.courses.server_services'), // 123
            $this->trans('education.courses.workstation_setup'), // 304
            $this->trans('education.courses.os_administration'), // 305
            $this->trans('education.courses.network_components'), // 129
            $this->trans('education.courses.office_tools'), // 301
            $this->trans('education.courses.office_advanced'), // 302
            $this->trans('education.courses.user_instruction'), // 214
            $this->trans('education.courses.security_encryption'), // 114
            $this->trans('education.courses.app_security'), // 183
            $this->trans('education.courses.automation_tasks'), // 121
            $this->trans('education.courses.scripting'), // 122
            $this->trans('education.courses.teamwork'), // 213
            $this->trans('education.courses.small_projects'), // 306
        ];
    }
}
