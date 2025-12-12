package com.brasibturger.services;

import com.brasibturger.dtos.ZoneDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Zone;
import com.brasibturger.repositories.ZoneRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class ZoneService {
    
    private final ZoneRepository zoneRepository;
    
    public List<ZoneDTO> getAllZones() {
        log.info("Retrieving all zones");
        return zoneRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public ZoneDTO getZoneById(Long id) {
        log.info("Retrieving zone with id: {}", id);
        Zone zone = zoneRepository.findById(id)
                .orElseThrow(() -> new ApiException("Zone not found with id: " + id, 404));
        return convertToDTO(zone);
    }
    
    public ZoneDTO getZoneByName(String nomZone) {
        log.info("Retrieving zone with name: {}", nomZone);
        Zone zone = zoneRepository.findByNomZone(nomZone)
                .orElseThrow(() -> new ApiException("Zone not found with name: " + nomZone, 404));
        return convertToDTO(zone);
    }
    
    public ZoneDTO createZone(ZoneDTO zoneDTO) {
        log.info("Creating new zone: {}", zoneDTO.getNomZone());
        Zone zone = convertToEntity(zoneDTO);
        Zone saved = zoneRepository.save(zone);
        return convertToDTO(saved);
    }
    
    public ZoneDTO updateZone(Long id, ZoneDTO zoneDTO) {
        log.info("Updating zone with id: {}", id);
        Zone zone = zoneRepository.findById(id)
                .orElseThrow(() -> new ApiException("Zone not found with id: " + id, 404));
        
        zone.setNomZone(zoneDTO.getNomZone());
        zone.setPrixLivraison(zoneDTO.getPrixLivraison());
        zone.setDescription(zoneDTO.getDescription());
        
        Zone updated = zoneRepository.save(zone);
        return convertToDTO(updated);
    }
    
    public void deleteZone(Long id) {
        log.info("Deleting zone with id: {}", id);
        if (!zoneRepository.existsById(id)) {
            throw new ApiException("Zone not found with id: " + id, 404);
        }
        zoneRepository.deleteById(id);
    }
    
    private ZoneDTO convertToDTO(Zone zone) {
        return ZoneDTO.builder()
                .nomZone(zone.getNomZone())
                .prixLivraison(zone.getPrixLivraison())
                .description(zone.getDescription())
                .build();
    }
    
    private Zone convertToEntity(ZoneDTO zoneDTO) {
        return Zone.builder()
                .nomZone(zoneDTO.getNomZone())
                .prixLivraison(zoneDTO.getPrixLivraison())
                .description(zoneDTO.getDescription())
                .build();
    }
}
