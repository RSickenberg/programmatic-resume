<?php

declare(strict_types=1);

namespace Romainsickenberg\ProgrammaticResume\Generator;

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

final class SupportN1N2 extends BaseResume
{
    /**
     * Companies whose work experience reads as support/helpdesk-relevant
     * (device fleet management, monitoring, customer-facing procedures),
     * as opposed to the software-engineering-heavy roles.
     * @var list<string>
     */
    private const array RELEVANT_COMPANIES = ['Ilem Group', 'Securitas AG'];

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

    public function addWorks(ResumeBuilder $builder): ResumeBuilder
    {
        $experiences = [];
        foreach ($this->getAllWorkExperiences() as $sectorExperiences) {
            foreach ($sectorExperiences as $experience) {
                if (\in_array($experience->name, self::RELEVANT_COMPANIES, true)) {
                    $experiences[] = $experience;
                }
            }
        }

        if (\count($experiences) !== \count(self::RELEVANT_COMPANIES)) {
            throw new \RuntimeException(
                'SupportN1N2::RELEVANT_COMPANIES no longer matches the company names in BaseResume::getAllWorkExperiences().',
            );
        }

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
                        $this->trans('skills.support_os_linux_basics'),
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
                        $this->trans('skills.support_networking_wifi_troubleshooting'),
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
}
