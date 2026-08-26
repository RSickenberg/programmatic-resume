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
    /**
     * Set to true to show the exact same work history as BackendDev instead
     * of the software-engineering placeholder below. Left as a manual
     * toggle: flip by hand once a decision is made on which reads better.
     */
    private const bool USE_BACKEND_DEV_EXPERIENCE = false;

    #[\Override]
    public function basics(): Basics
    {
        return new Basics(
            name: self::FULL_NAME,
            label: $this->trans('basics.support_position'),
            email: new Email(self::EMAIL),
            phone: self::PHONE,
            url: new Url(self::URL),
            summary: $this->getSummary(),
            location: $this->getLocation(),
            profiles: $this->getRelatedProfiles(),
        );
    }

    /**
     * The individual IT-sector jobs are backend/full-stack development, so
     * listing them with their dev-specific highlights reads as off-target for
     * an IT Support N1/N2/N3 role. They are folded into one entry instead,
     * but that entry carries real, support-relevant highlights (device fleet
     * rollout, incident response, server administration) drawn from the same
     * history, rather than a disclaimer telling the reader to discount it.
     */
    public function addWorks(ResumeBuilder $builder): ResumeBuilder
    {
        if (self::USE_BACKEND_DEV_EXPERIENCE) {
            return $this->addAllWorkExperiences($builder);
        }

        $itExperiences = $this->getAllWorkExperiences()->get(WorkTypes::IT->value);

        $builder->addWork(new Work(
            name: $this->trans('work.software_engineering_summary.name'),
            position: $this->trans('work.software_engineering_summary.position'),
            startDate: min(array_map(static fn(Work $w) => $w->startDate, $itExperiences)),
            endDate: max(array_map(static fn(Work $w) => $w->endDate, $itExperiences)),
            summary: $this->trans('work.software_engineering_summary.summary'),
            highlights: [
                $this->trans('work.software_engineering_summary.highlight_1'),
                $this->trans('work.software_engineering_summary.highlight_2'),
                $this->trans('work.software_engineering_summary.highlight_3'),
                $this->trans('work.software_engineering_summary.highlight_4'),
                $this->trans('work.software_engineering_summary.highlight_5'),
            ],
        ));

        return $builder;
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
                        'Jira Service Management',
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
                        'Microsoft 365 (Outlook, Teams, SharePoint)',
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
