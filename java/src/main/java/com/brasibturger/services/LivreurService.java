package com.brasibturger.services;

import com.brasibturger.dtos.LivreurDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Livreur;
import com.brasibturger.models.Zone;
import com.brasibturger.repositories.LivreurRepository;
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
public class LivreurService {
    
    private final LivreurRepository livreurRepository;
    private final ZoneRepository zoneRepository;
    
    public List<LivreurDTO> getAllLivreurs() {
        log.info("Retrieving all livreurs");
        return livreurRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<LivreurDTO> getActiveLivreurs() {
        log.info("Retrieving active livreurs");
        return livreurRepository.findByStatut(Livreur.Statut.ACTIF.toString()).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<LivreurDTO> getLivreursByZone(Long zoneId) {
        log.info("Retrieving livreurs for zone: {}", zoneId);
        return livreurRepository.findByZoneIdZone(zoneId).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public LivreurDTO getLivreurById(Long id) {
        log.info("Retrieving livreur with id: {}", id);
        Livreur livreur = livreurRepository.findById(id)
                .orElseThrow(() -> new ApiException("Livreur not found with id: " + id, 404));
        return convertToDTO(livreur);
    }
    
    public LivreurDTO createLivreur(LivreurDTO livreurDTO) {
        log.info("Creating new livreur: {} {}", livreurDTO.getNom(), livreurDTO.getPrenom());
        
        Zone zone = zoneRepository.findById(livreurDTO.getIdZone())
                .orElseThrow(() -> new ApiException("Zone not found with id: " + livreurDTO.getIdZone(), 404));
        
        Livreur livreur = convertToEntity(livreurDTO);
        livreur.setZone(zone);
        livreur.setStatut(Livreur.Statut.ACTIF);
        
        Livreur saved = livreurRepository.save(livreur);
        return convertToDTO(saved);
    }
    
    public LivreurDTO updateLivreur(Long id, LivreurDTO livreurDTO) {
        log.info("Updating livreur with id: {}", id);
        Livreur livreur = livreurRepository.findById(id)
                .orElseThrow(() -> new ApiException("Livreur not found with id: " + id, 404));
        
        livreur.setNom(livreurDTO.getNom());
        livreur.setPrenom(livreurDTO.getPrenom());
        livreur.setTelephone(livreurDTO.getTelephone());
        livreur.setStatut(Livreur.Statut.valueOf(livreurDTO.getStatut()));
        
        if (livreurDTO.getIdZone() != null) {
            Zone zone = zoneRepository.findById(livreurDTO.getIdZone())
                    .orElseThrow(() -> new ApiException("Zone not found with id: " + livreurDTO.getIdZone(), 404));
            livreur.setZone(zone);
        }
        
        Livreur updated = livreurRepository.save(livreur);
        return convertToDTO(updated);
    }
    
    public void deleteLivreur(Long id) {
        log.info("Deleting livreur with id: {}", id);
        if (!livreurRepository.existsById(id)) {
            throw new ApiException("Livreur not found with id: " + id, 404);
        }
        livreurRepository.deleteById(id);
    }
    
    private LivreurDTO convertToDTO(Livreur livreur) {
        return LivreurDTO.builder()
                .nom(livreur.getNom())
                .prenom(livreur.getPrenom())
                .telephone(livreur.getTelephone())
                .idZone(livreur.getZone() != null ? livreur.getZone().getIdZone() : null)
                .statut(livreur.getStatut().toString())
                .build();
    }
    
    private Livreur convertToEntity(LivreurDTO livreurDTO) {
        return Livreur.builder()
                .nom(livreurDTO.getNom())
                .prenom(livreurDTO.getPrenom())
                .telephone(livreurDTO.getTelephone())
                .build();
    }
}
